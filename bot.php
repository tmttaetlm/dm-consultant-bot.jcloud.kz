<?php
    $body = file_get_contents('php://input'); 
    $data = json_decode($body, true); 
    
    include_once('tgclass.php');
    //include_once('messageHandler.php');
    //include_once('callbackHandler.php');

    $bot = new TG('2099253626:AAH8wHq3fRhWpNB3By6R5u2FLBdoLWfdOso');

    $chatId = $data['message']['chat']['id'];

    if ($text == '/start') {
        $answer = "Добро пожаловать!\nИнтерфейс тілін таңданыз\n----------------------------------\nВыберите язык интерфейса";
        $keyboard = ["inline_keyboard" => [[['text' => 'Қазақша', 'callback_data' => 'kaz'],['text' => 'Русский', 'callback_data' => 'rus']]]];
        $bot->send($chatId,$answer,'HTML',$keyboard);
    };

    /*if ($data['message']) {
        messageHandler($data, $chatId, $data['message']['text']);
    }
    if ($data['callback_query']) {
        callbackHandler($data, $chatId, $data['callback_query']['data']);
    }*/

    /*function newUser($params)
    {
        $query = "INSERT INTO sd_tickets (type,hardware,description,customer,datetime,priority,contact,executor,status)
                  VALUES (:type,:hardware,:description,:customer,:datetime,:priority,:contact,:executor,'send');";
        $db = Db::getDb();
        $db->selectQuery($query,$params);

        $recipient = 'helpshamdan@gmail.com';
        $text = 'Ваша заявка отправлена в службу поддержки ТОО "ШамДан". После исполнения заявки вы получите уведомление на почту.';
        sendMail($recipient, $text);
        $recipient = 'info@shamdan.kz';
        $text = 'Получена новая заявка.';
        sendMail($recipient, $text);
    }*/
?>
