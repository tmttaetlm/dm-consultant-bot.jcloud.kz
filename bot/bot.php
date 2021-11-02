<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    include_once ROOT.'/application/Components/Db.php';
    include_once ROOT.'/bot/tgclass.php';
    use Components\Db;

    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    file_put_contents('log', '['.(date('Y-m-d H:i:s')).'] '.(json_encode($data, JSON_UNESCAPED_UNICODE))."\n", FILE_APPEND);
    //$bot = new TG('2099253626:AAH8wHq3fRhWpNB3By6R5u2FLBdoLWfdOso');
    $bot = new TG('2061690426:AAEx1ss_tw2MefiudZdpCftqGBqJ7O-1QcI');
	/*try {
    	$bot->send('248598993', json_encode($data, JSON_UNESCAPED_UNICODE), 'HTML');
	} catch (Exception $e) {
      	file_put_contents('log', '['.(date('Y-m-d H:i:s')).'] '.($e->getMessage())."\n", FILE_APPEND);
	}*/
    
    if (array_key_exists('message', $data)) {
        $chatId = $data['message']['chat']['id'];
      	$text = $data['message']['text'];
        $name = $data['message']['chat']['username'];
        $adminId = Db::getDb()->execQuery('SELECT value FROM params WHERE name="admin_id"', [])[0]['value'];
        $isAdmin = $adminId == $chatId ? true : false;
      	array_key_exists('first_name', $data['message']['chat']) ? $firstName = $data['message']['chat']['first_name'] : $firstName = '';
      	array_key_exists('last_name', $data['message']['chat']) ? $lastName = $data['message']['chat']['last_name'] : $lastName = '';
        if (array_key_exists('text', $data['message'])) {
            if ($data['message']['text'] == '/start') {
                if (empty(Db::getDb()->execQuery('SELECT tgId FROM users WHERE tgId = :tgId', ['tgId' => $chatId]))) {
                    $msg = Db::getDb()->execQuery('SELECT value FROM params WHERE name = "welcome_text"', []);
                    $keyboard = ["inline_keyboard" => [[['text' => 'Қазақша', 'callback_data' => 'kaz'], ['text' => 'Русский', 'callback_data' => 'rus']]]];
                    $bot->send($chatId, $msg[0]['value'], 'HTML', $keyboard);
                    $params = ['tgId' => $chatId, 'tgName' => $name, 'firstName' => $firstName, 'lastName' => $lastName];
                    Db::getDb()->execQuery('INSERT INTO users (tgId, tgName, firstName, lastName) VALUES (:tgId, :tgName, :firstName, :lastName)', $params);
                } else {
                    $msg = "С возвращением ".$name."!";
                    $menu = $isAdmin ? adminMenu() : mainMenu();
                    $bot->send($chatId, $msg, 'HTML', $menu);
                }
                return;
            }
        }
        messageHandler($bot, $chatId, $name, $text, $data, $adminId);
    }
    if (array_key_exists('callback_query', $data)) {
        $chatId = $data['callback_query']['from']['id'];
        $adminId = Db::getDb()->execQuery('SELECT value FROM params WHERE name="admin_id"', [])[0]['value'];
        $isAdmin = $adminId == $chatId ? true : false;
        switch ($data['callback_query']['data']) {
            case 'kaz':
                $msg = "Сіз қазақ тілін таңданыңыз.";
                $menu = $isAdmin ? adminMenu() : mainMenu();
                $bot->send($chatId, $msg, 'HTML', $menu);
                Db::getDb()->execQuery('UPDATE users SET lang = "kaz" WHERE tgId = :tgId', ['tgId' => $chatId]);
                //$msg = "Төмендегі батырманы басыпб ботқа өз телефон номеріңізді жіберіңіз.";
                //$keyboard = ["keyboard" => [[['text' => 'Отправить номер телефона', 'request_contact' => true]]], 'resize_keyboard' => true];
                //$bot->send($chatId, $msg, 'HTML', $keyboard);
                break;
            case 'rus':
                $msg = "Вы выбрали русский язык.";
                $menu = $isAdmin ? adminMenu() : mainMenu();
                    $bot->send($chatId, $msg, 'HTML', $menu);
                Db::getDb()->execQuery('UPDATE users SET lang = "rus" WHERE tgId = :tgId', ['tgId' => $chatId]);
                //$msg = "Отправьте боту свой номер телефона, нажав на кнопку ниже.";
                //$keyboard = ["keyboard" => [[['text' => 'Отправить номер телефона', 'request_contact' => true]]], 'resize_keyboard' => true];
                //$bot->send($chatId, $msg, 'HTML', $keyboard);
                break;
            default:
                callbackHandler($bot, $chatId, $data['callback_query']['from']['username'], $data['callback_query']['data'], $adminId);
                break;
        }
    }

	function messageHandler($bot, $chatId, $name, $text, $data, $adminId) {
        /*if (array_key_exists('contact', $data['message'])) {
            $chatId = $data['message']['chat']['id'];
            $values = ["phone = ".$data['message']['contact']['phone_number']];
            updateData('users', $values, 'tgId = '.$chatId);
        }*/
        $step = Db::getDb()->execQuery('SELECT gold_step FROM users WHERE tgId = :tgId', ['tgId' => $chatId]);
        switch ($step[0]['gold_step']) {
            case '3':
                $price = 14300;
                $sum = $text*$price;
                $msg = "Сумма за изделие: ".$sum;
                $bot->send($chatId, $msg, 'HTML');
                $msg = "Вы можете также зафиксировать сумму.\n\nЭто действие вас ни к чему не обязывает. Через 12 часов расчет просто будет удален.";
                $keyboard = ["inline_keyboard" => [[['text' => 'Зафиксировать сумму на 12 часов', 'callback_data' => 'fix_summ']],
                                                   [['text' => 'Пропустить', 'callback_data' => 'skip_summ']]]];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                $params = ['tgId' => $chatId, 'number_param' => $text, 'price' => $sum];
                Db::getDb()->execQuery('UPDATE tickets SET number_param = :number_param, price = :price WHERE tgId = :tgId AND status = 1', $params);
                Db::getDb()->execQuery('UPDATE users SET gold_step = 4 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case '4':
                $params = ['tgId' => $chatId, 'comment' => $text];
                Db::getDb()->execQuery('UPDATE tickets SET comment = :comment WHERE tgId = :tgId AND status = 1', $params);
                $msg = "Ваша заявка принята, с Вами свяжется консультант.";
                $bot->send($chatId, $msg, 'HTML');
                break;
        }
        $step = Db::getDb()->execQuery('SELECT electronics_step FROM users WHERE tgId = :tgId', ['tgId' => $chatId]);
        switch ($step[0]['electronics_step']) {
            case '3':
                $msg = "Отлично, добавьте комментарий?";
                $keyboard = ["inline_keyboard" => [[['text' => 'Пропустить', 'callback_data' => 'skip_comment']]]];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                $params = ['tgId' => $chatId, 'number_param' => $text];
                Db::getDb()->execQuery('UPDATE tickets SET number_param = :number_param WHERE tgId = :tgId AND status = 1', $params);
                Db::getDb()->execQuery('UPDATE users SET electronics_step = 4 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case '4':
                $msg = "Отправьте фото техники";
                $keyboard = ["inline_keyboard" => [[['text' => 'Пропустить', 'callback_data' => 'skip_photo']]]];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                $params = ['tgId' => $chatId, 'comment' => $text];
                Db::getDb()->execQuery('UPDATE tickets SET comment = :comment WHERE tgId = :tgId AND status = 1', $params);
                Db::getDb()->execQuery('UPDATE users SET electronics_step = 5 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case '5':
                $msg = "Ваша заявка принята, с Вами свяжется консультант.";
                $bot->send($chatId, $msg, 'HTML');
                $params = ['tgId' => $chatId, 'photo' => $data['message']['photo'][0]['file_id']];
                Db::getDb()->execQuery('UPDATE tickets SET photo = :photo WHERE tgId = :tgId AND status = 1', $params);
                Db::getDb()->execQuery('UPDATE tickets SET status = 2 WHERE tgId = :tgId AND status = 1', ['tgId' => $chatId]);
                Db::getDb()->execQuery('UPDATE users SET electronics_step = null WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            default:
                # code...
                break;
        }
        switch ($text) {
            case '👩🏻‍💻 Получить консультацию':
                // status: 1 - активный; 2 - в ожидании; 3 - завершенный;
                $active_conversation = Db::getDb()->execQuery('SELECT * FROM conversations WHERE status IS NOT NULL AND status=1', []);
                if (empty($active_conversation)) {
                    $msg = "Напишите Ваш вопрос\nНа связи консультант: ";
                    $msg .= Db::getDb()->execQuery('SELECT value FROM params WHERE name = "admin_name"', [])[0]['value'];
                    $keyboard = ["keyboard" => [[['text' => 'Завершить консультацию']]], 'resize_keyboard' => true, 'one_time_keyboard' => true];
                    $bot->send($chatId, $msg, 'HTML', $keyboard);
                    $msg = 'Обращение за консультацией:';
                    $msg .= "\n<b>Пользователь: ".$name."</b>";
                    $msg .= "\n<b>ID: ".$chatId."</b>";
                    $keyboard = ["keyboard" => [[['text' => 'Завершить консультацию']]], 'resize_keyboard' => true, 'one_time_keyboard' => true];
                    $bot->send($adminId, $msg, 'HTML', $keyboard);
                    $params = ['tgId' => $chatId, 'tgName' => $name, 'datetime' => date('Y-m-d H:i:s'), 'status' => 1];
                    Db::getDb()->execQuery('INSERT INTO conversations (tgId, tgName, datetime, status) VALUES (:tgId, :tgName, :datetime, :status)', $params);
                } else {
                    $msg = 'На данный момент консультант занят. Консультант Вам ответит сразу же как освободится.';
                    $params = ['tgId' => $chatId, 'tgName' => $name, 'datetime' => date('Y-m-d H:i:s'), 'status' => 2];
                    Db::getDb()->execQuery('INSERT INTO conversations (tgId, tgName, datetime, status) VALUES (:tgId, :tgName, :datetime, :status)', $params);
                    $bot->send($chatId, $msg, 'HTML');
                }
                break;
            case '💍 Деньги под залог золота/изделия':
                // status: 1 - активный; 2 - заполнен клиентом, в обработке; 3 - исполненный; 4 - сумма зафиксирована;
                $msg = "Рассчитайте прямо сейчас сумму, которую вы получите за ваше изделие.";
                $bot->send($chatId, $msg, 'HTML');
                $msg = "Выберите тип оценки. Мы можем оценить изделие как золото или как индивидуальную авторскую работу, изделие";
                $keyboard = ["inline_keyboard" => [[['text' => 'Золото', 'callback_data' => 'Золото'], ['text' => 'Изделие', 'callback_data' => 'Изделие']]]];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                Db::getDb()->execQuery('UPDATE users SET jewel_step = 1, gold_step = 1 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case '💻 Деньги под залог техники':
                // status: 1 - активный; 2 - заполнен клиентом, в обработке; 3 - исполненный;
                $msg = "Рассчитайте прямо сейчас сумму, которую вы получите за вашу технику.";
                $bot->send($chatId, $msg, 'HTML');
                $electronics = Db::getDb()->execQuery('SELECT name FROM electronics', []);
                $elName = [];
                for ($i=0; $i<=count($electronics)-1; $i+=2) {
                    $kb = [];
                    for ($j=0; $j<=1; $j++) { 
                        if ($i+$j > count($electronics)-1) break;
                        $btn = [];
                        $btn['text'] = $electronics[$i+$j]['name'];
                        $btn['callback_data'] = $electronics[$i+$j]['name'];
                        array_push($kb, $btn);
                    }
                    array_push($elName, $kb);
                }
                $msg = "Укажите тип устройства";
                $keyboard = ["inline_keyboard" => $elName];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                Db::getDb()->execQuery('UPDATE users SET electronics_step = 1 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case '💰 Деньги до зарплаты (без залога)':
                $msg = "“Деньги Маркет”- это компания республиканского значения, которая предлагает микрокредит по самым выгодным условиям!";
                $msg .= "\n\nМы гарантируем:\n- Высокую вероятность одобрения;\n- Быстрое оформление заявки;\n- САМЫЕ низкие ставки;\n- Особые условия для постоянных клиентов!";
                $msg .= "\n\nОформить микрокредит можно:\nв одном из 200 наших отделений\nв одном из 49 деньгоматов";
                $msg .= "\nОНЛАЙН: через личный кабинет на нашем сайте https://dengiclick.kz/ или в удобном мобильном приложении https://play.google.com/store/apps/details?id=kz.dengigroup.dengiclick";
                $msg .= "\n\nДля оформления понадобится:\n- Удостоверение личности;\n- мобильный телефон.";
                $msg .= "\n\nБОЛЕЕ 3 Акций в которых Вы можете: выиграть прощение микрокредита, получить деньги за приведенного друга, получить статус лояльного клиента и оформлять микрокредит по самым низким ставкам!";
                $msg .= "Активная социальная сеть instagram, где вы можете получить обратную связь и быть в курсе всех новых событий!\n\nМы лучшие на этом рынке! Хочешь убедиться? Попробуй подать заявку.";
                $msg .= "\nИли позвонить по номеру +7-700-700-7707";
                $keyboard = ["inline_keyboard" => [[['text' => '📥 Подать заявку', 'url' => 'https://dengiclick.kz']],
                                                   [['text' => '📲 Скачать приложение', 'url' => 'https://play.google.com/store/apps/details?id=kz.dengigroup.dengiclick']]]];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                break;
            case '🚗 Деньги под залог автомобиля':
                $msg = "<b>Информация:</b>\n\t- Минимальный платёж 1 200 тенге в день на 1 000 000\n\t- Сумма до 10 000 000 тг\n\t- Отсутствие GPS\n\t- Без Комиссий\n\t- Без Страховок\n\t- Частичное досрочное погашение в любое удобное время без штрафов\n\t- Высокая Оценочная стоимость автомобиля\n\t- Без Генеральной доверенности\n\t- Без гарантов и поручителей\n\nОформление по двум документам: УД/вид на жительство и Технический паспорт автомобиля\n\nВ залог принимаются автомобили и техника учета РК.";
                $bot->send($chatId, $msg, 'HTML');
                $msg = "Рассчитайте самостоятельно ежемесячный платеж и сумму переплат";
                $keyboard = ["inline_keyboard" => [[['text' => 'Сайт avto-dengi.kz', 'url' => 'https://avto-dengi.kz']]]];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                break;
            case '🏢 Деньги под залог недвижимости':
                $msg = "Чтобы узнать условия просим Вас перейти на сайт";
                $keyboard = ["inline_keyboard" => [[['text' => 'Сайт Деньги Маркет', 'url' => 'https://dengiclick.kz']]]];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                break;
            case '🗂 Личный кабинет':
                $msg = "Для входа в личный кабинет перейдите по ссылке ниже";
                $keyboard = ["inline_keyboard" => [[['text' => 'Сайт Деньги Маркет', 'url' => 'https://dengiclick.kz']]]];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                break;
            case '📍 Адреса отделений':
                $msg = "Выберите область";
                $regions = Db::getDb()->execQuery('SELECT name FROM regions', []);
                $regName = [];
                for ($i=0; $i<=count($regions)-1; $i+=2) {
                    $kb = [];
                    for ($j=0; $j<=1; $j++) { 
                        if ($i+$j > count($regions)-1) break;
                        $btn = [];
                        $btn['text'] = $regions[$i+$j]['name'];
                        $btn['callback_data'] = $regions[$i+$j]['name'];
                        array_push($kb, $btn);
                    }
                    array_push($regName, $kb);
                }
            	$keyboard = ["inline_keyboard" => $regName];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                Db::getDb()->execQuery('UPDATE users SET offices_step = 1 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case 'Завершить консультацию':
                $convId = Db::getDb()->execQuery('SELECT tgId FROM conversations WHERE status = 1', []);
                $msg = "Спасибо за обращение!";
                $bot->send($convId[0]['tgId'], $msg, 'HTML', mainMenu());
                if ($convId[0]['tgId'] == $chatId) {
                    $msg = "Клиент завершил консультацию:";
                    $msg .= "\n<b>Пользователь: ".$name."</b>";
                    $msg .= "\n<b>ID: ".$chatId."</b>";
                    $bot->send($adminId, $msg, 'HTML', adminMenu());
                }
                Db::getDb()->execQuery('UPDATE conversations SET status = 3 WHERE status = 1', []);
                $inWaiting = Db::getDb()->execQuery('SELECT * FROM conversations WHERE status = 2', []);
                if (!empty($inWaiting)) {
                    Db::getDb()->execQuery('UPDATE conversations SET status = 1 WHERE id = :id', ['id' => $inWaiting[0]['id']]);
                    $msg = "Напишите Ваш вопрос\nНа связи консультант: ";
                    $msg .= Db::getDb()->execQuery('SELECT value FROM params WHERE name = "admin_name"', [])[0]['value'];
                    $keyboard = ["keyboard" => [[['text' => 'Завершить консультацию']]], 'resize_keyboard' => true, 'one_time_keyboard' => true];
                    $bot->send($inWaiting[0]['tgId'], $msg, 'HTML', $keyboard);
                    $msg = 'Обращение за консультацией:';
                    $msg .= "\n<b>Пользователь: ".$inWaiting[0]['tgName']."</b>";
                    $msg .= "\n<b>ID: ".$inWaiting[0]['tgId']."</b>";
                    $keyboard = ["keyboard" => [[['text' => 'Завершить консультацию']]], 'resize_keyboard' => true, 'one_time_keyboard' => true];
                    $bot->send($adminId, $msg, 'HTML', $keyboard);
                }
                break;                
            case 'Тест':
                $msg = "Работает";
                $bot->send($chatId, $msg, 'HTML');
                break;
          	default:
                if (!empty(Db::getDb()->execQuery('SELECT * FROM conversations WHERE status = 1', ['tgId' => $chatId]))) {
                  	$curConv = Db::getDb()->execQuery('SELECT tgId FROM conversations WHERE status = 1', [])[0]['tgId'];
                    if ($adminId == $chatId) {
                        $msg = "<b>Ответ консультанта</b>\n\n";
                        $msg .= $text; 
                        $bot->send($curConv, $msg, 'HTML');
                    } else {
                        $msg = "<b>Пользователь: ".$name."</b>\n";
                        $msg .= "<b>ID: ".$chatId."</b>\n\n";
                        $msg .= $text;
                        $keyboard = ["keyboard" => [[['text' => 'Завершить консультацию']]], 'resize_keyboard' => true, 'one_time_keyboard' => true];
                        $bot->send($adminId, $msg, 'HTML', $keyboard);
                    }
                }/* else {
            	    $bot->send($chatId, 'Неизвестный пункт меню', 'HTML');
                }*/
        }
    }

    function callbackHandler($bot, $chatId, $name, $callback, $adminId)
    {
        if ($callback == 'skip_comment') {
            $msg = "Отправьте фото техники";
            $keyboard = ["inline_keyboard" => [[['text' => 'Пропустить', 'callback_data' => 'skip_photo']]]];
            $bot->send($chatId, $msg, 'HTML', $keyboard);
            Db::getDb()->execQuery('UPDATE users SET electronics_step = 5 WHERE tgId = :tgId', ['tgId' => $chatId]);
            return;
        }
        if ($callback == 'skip_photo') {
            $msg = "Ваша заявка принята, с Вами свяжется консультант.";
            $bot->send($chatId, $msg, 'HTML');
            Db::getDb()->execQuery('UPDATE tickets SET status = 2 WHERE tgId = :tgId AND status = 1', ['tgId' => $chatId]);
            Db::getDb()->execQuery('UPDATE users SET electronics_step = null WHERE tgId = :tgId', ['tgId' => $chatId]);
            return;
        }
        if ($callback == 'Золото') {
            $msg = "Укажите пробу";
            $keyboard = ["inline_keyboard" => [[['text' => '375', 'callback_data' => '375'], ['text' => '500', 'callback_data' => '500'], ['text' => '585', 'callback_data' => '585']], 
                                               [['text' => '750', 'callback_data' => '750'], ['text' => '999', 'callback_data' => '999']]]];
            $bot->send($chatId, $msg, 'HTML', $keyboard);
            $params = ['tgId' => $chatId, 'tgName' => $name, 'branch' => $callback, 'status' => 1];
            Db::getDb()->execQuery('INSERT INTO tickets (tgId, tgName, branch, status) VALUES (:tgId, :tgName, :branch, :status)', $params);
            Db::getDb()->execQuery('UPDATE users SET jewel_step = null, gold_step = 2 WHERE tgId = :tgId', ['tgId' => $chatId]);
            return;
        }
        if ($callback == 'Изделие') {
            $jewels = Db::getDb()->execQuery('SELECT name FROM jewels', []);
            $jName = [];
            for ($i=0; $i<=count($jewels)-1; $i+=2) {
                $kb = [];
                for ($j=0; $j<=1; $j++) { 
                    if ($i+$j > count($jewels)-1) break;
                    $btn = [];
                    $btn['text'] = $jewels[$i+$j]['name'];
                    $btn['callback_data'] = $jewels[$i+$j]['name'];
                    array_push($kb, $btn);
                }
                array_push($jName, $kb);
            }
            $msg = "Укажите тип изделия из списка";
            $keyboard = ["inline_keyboard" => $jName];
            $bot->send($chatId, $msg, 'HTML', $keyboard);
            $params = ['tgId' => $chatId, 'tgName' => $name, 'branch' => $callback, 'status' => 1];
            Db::getDb()->execQuery('INSERT INTO tickets (tgId, tgName, branch, status) VALUES (:tgId, :tgName, :branch, :status)', $params);
            Db::getDb()->execQuery('UPDATE users SET jewel_step = 2, gold_step = null WHERE tgId = :tgId', ['tgId' => $chatId]);
            return;
        }
        if ($callback == 'fix_summ') {
            $msg = "Сумма зафиксированна, наш менеджер свяжется с Вами, Удачного Вам дня!";
            $bot->send($chatId, $msg, 'HTML');
            Db::getDb()->execQuery('UPDATE tickets SET status = 4 WHERE tgId = :tgId AND status = 1', ['tgId' => $chatId]);
            Db::getDb()->execQuery('UPDATE users SET gold_step = null WHERE tgId = :tgId', ['tgId' => $chatId]);
            return;
        }
      	if ($callback == 'skip_summ') {
            $msg = "Спасибо за обращение, Удачного Вам дня!";
            $bot->send($chatId, $msg, 'HTML');
            Db::getDb()->execQuery('UPDATE tickets SET status = 4 WHERE tgId = :tgId AND status = 1', ['tgId' => $chatId]);
            Db::getDb()->execQuery('UPDATE users SET gold_step = null WHERE tgId = :tgId', ['tgId' => $chatId]);
            return;
        }

        // под залог золота/изделия
        $gold_step = Db::getDb()->execQuery('SELECT gold_step FROM users WHERE tgId = :tgId', ['tgId' => $chatId]);
        if ($gold_step[0]['gold_step'] == 2) {
            $msg = "Укажите число грамм (пример: 3, 5)";
            $bot->send($chatId, $msg, 'HTML');
            $params = ['tgId' => $chatId, 'model' => $callback];
            Db::getDb()->execQuery('UPDATE tickets SET model = :model WHERE tgId = :tgId AND status = 1', $params);
            Db::getDb()->execQuery('UPDATE users SET gold_step = 3 WHERE tgId = :tgId', ['tgId' => $chatId]);
            return;
        }
        $jewel_step = Db::getDb()->execQuery('SELECT jewel_step FROM users WHERE tgId = :tgId', ['tgId' => $chatId]);
        if ($jewel_step[0]['jewel_step'] == 2) {
            $jewels = Db::getDb()->execQuery('SELECT * FROM jewels', []);
            $search_jewels = array_search($callback, array_column($jewels, 'name'));
            $msg = "Чтобы оценить стоимость Вашего изделия перейдите на сайт:";
            $keyboard = ["inline_keyboard" => [[['text' => 'Сайт Деньги Маркет', 'url' => 'https://dengiclick.kz']]]];
            $bot->send($chatId, $msg, 'HTML', $keyboard);
            $params = ['tgId' => $chatId, 'model' => $callback];
            Db::getDb()->execQuery('UPDATE tickets SET model = :model WHERE tgId = :tgId AND status = 1', $params);
            Db::getDb()->execQuery('UPDATE tickets SET status = 2 WHERE tgId = :tgId AND status = 1', ['tgId' => $chatId]);
            Db::getDb()->execQuery('UPDATE users SET jewel_step = null WHERE tgId = :tgId', ['tgId' => $chatId]);
            return;
        }
        //--------------------------------------------------------------------------

        // под залог техники
        $step = Db::getDb()->execQuery('SELECT electronics_step FROM users WHERE tgId = :tgId', ['tgId' => $chatId]);
        $electronics = Db::getDb()->execQuery('SELECT * FROM electronics', []);
        $model_parent = array_search($callback, array_column($electronics, 'name'));
        switch ($step[0]['electronics_step']) {
            case '1':
                $models = Db::getDb()->execQuery('SELECT name FROM models WHERE parent = :id', ['id' => $electronics[$model_parent]['id']]);
                $mName = [];
                for ($i=0; $i<=count($models)-1; $i+=2) {
                    $kb = [];
                    for ($j=0; $j<=1; $j++) { 
                        if ($i+$j > count($models)-1) break;
                        $btn = [];
                        $btn['text'] = $models[$i+$j]['name'];
                        $btn['callback_data'] = $models[$i+$j]['name'];
                        array_push($kb, $btn);
                    }
                    array_push($mName, $kb);
                }
                $msg = "Укажите модель";
                $keyboard = ["inline_keyboard" => $mName];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                $params = ['tgId' => $chatId, 'tgName' => $name, 'branch' => $callback, 'status' => 1];
                Db::getDb()->execQuery('INSERT INTO tickets (tgId, tgName, branch, status) VALUES (:tgId, :tgName, :branch, :status)', $params);
                Db::getDb()->execQuery('UPDATE users SET electronics_step = 2 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case '2':
                $msg = "Укажите год";
                $bot->send($chatId, $msg, 'HTML');
                $params = ['tgId' => $chatId, 'model' => $callback];
                Db::getDb()->execQuery('UPDATE tickets SET model = :model WHERE tgId = :tgId AND status = 1', $params);
                Db::getDb()->execQuery('UPDATE users SET electronics_step = 3 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
        }
        //--------------------------------------------------------------------------

        // Адреса отделений
        $offices_step = Db::getDb()->execQuery('SELECT offices_step FROM users WHERE tgId = :tgId', ['tgId' => $chatId]);
        switch ($offices_step[0]['offices_step']) {
            case '1': 
                $regions = Db::getDb()->execQuery('SELECT * FROM regions', []);
                $region = array_search($callback, array_column($regions, 'name'));
                $cities = Db::getDb()->execQuery('SELECT name FROM cities WHERE region = :id', ['id' => $regions[$region]['id']]);
                $cityName = [];
                for ($i=0; $i<=count($cities)-1; $i+=2) {
                    $kb = [];
                    for ($j=0; $j<=1; $j++) { 
                        if ($i+$j > count($cities)-1) break;
                        $btn = [];
                        $btn['text'] = $cities[$i+$j]['name'];
                        $btn['callback_data'] = $cities[$i+$j]['name'];
                        array_push($kb, $btn);
                    }
                    array_push($cityName, $kb);
                }
                $msg = "Выберите город";
                $keyboard = ["inline_keyboard" => $cityName];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                Db::getDb()->execQuery('UPDATE users SET offices_step = 2 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case '2':
                $cities = Db::getDb()->execQuery('SELECT * FROM cities', []);
                $city = array_search($callback, array_column($cities, 'name'));
                $offices = Db::getDb()->execQuery('SELECT id, adres FROM offices WHERE city = :id', ['id' => $cities[$city]['id']]);
                $cityName = [];
                for ($i=0; $i<=count($offices)-1; $i+=2) {
                    $kb = [];
                    for ($j=0; $j<=1; $j++) { 
                        if ($i+$j > count($offices)-1) break;
                        $btn = [];
                        $btn['text'] = $offices[$i+$j]['adres'];
                        $btn['callback_data'] = $offices[$i+$j]['id'];
                        array_push($kb, $btn);
                    }
                    array_push($cityName, $kb);
                }
                $msg = "Выберите отделение";
                $keyboard = ["inline_keyboard" => $cityName];
                $bot->send($chatId, $msg, 'HTML', $keyboard);
                Db::getDb()->execQuery('UPDATE users SET offices_step = 3 WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
            case '3':
                $office = Db::getDb()->execQuery('SELECT * FROM offices WHERE id = :id', ['id' => $callback]);
                $city = Db::getDb()->execQuery('SELECT name FROM cities WHERE id = :id', ['id' => $office[0]['id']]);
                $msg = "Отделение в г. ".$city[0]['name'];
                $msg .= "\nАдрес: ".$office[0]['adres'];
                $msg .= "\nТелефон: ".$office[0]['phone'];
                if ($office[0]['media_url'] != '') {
                    $bot->sendWithPhoto($chatId, $msg, 'HTML', $office[0]['media_url']);
                } else {
                    $bot->send($chatId, $msg, 'HTML');
                }
                Db::getDb()->execQuery('UPDATE users SET offices_step = null WHERE tgId = :tgId', ['tgId' => $chatId]);
                break;
        }
        //--------------------------------------------------------------------------
    }

	function mainMenu() {
		return $keyboard = ["keyboard" => [
                [['text' => '👩🏻‍💻 Получить консультацию']], 
          		[['text' => '💍 Деньги под залог золота/изделия']], 
                [['text' => '💻 Деньги под залог техники']], 
                [['text' => '💰 Деньги до зарплаты (без залога)']], 
                [['text' => '🚗 Деньги под залог автомобиля']], 
                [['text' => '🏢 Деньги под залог недвижимости']], 
                [['text' => '📍 Адреса отделений']], 
                [['text' => '🗂 Личный кабинет']]
        	    ], 'resize_keyboard' => true];
        //$bot->send($chatId, 'Главное меню', 'HTML', $keyboard); 
	}
    function adminMenu() {
		return $keyboard = ["keyboard" => [
                [['text' => 'Список пользователей']]
        	    ], 'resize_keyboard' => true];
        //$bot->send($chatId, 'Главное меню', 'HTML', $keyboard); 
	}

    if (error_get_last()['message'] != '') {
        $bot->send('248598993', error_get_last()['message'].' at line '.error_get_last()['line'].' in '.error_get_last()['file'], 'HTML');
    }
?>
