<?php
    include_once 'db.php';
    include_once 'tgclass.php';

    use Db;

    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    file_put_contents('log','['.(date('Y-m-d H:i:s')).'] '.$body."\n",FILE_APPEND);
    $bot = new TG('2099253626:AAH8wHq3fRhWpNB3By6R5u2FLBdoLWfdOso');

    if (array_key_exists('message', $data)) {
        $chatId = $data['message']['chat']['id'];
        if ($data['message']['text'] == '/start') {
            $answer = "Добро пожаловать!\nИнтерфейс тілін таңданыз\n----------------------------------\nВыберите язык интерфейса";
            $keyboard = ["inline_keyboard" => [[['text' => 'Қазақша', 'callback_data' => 'kaz'],['text' => 'Русский', 'callback_data' => 'rus']]]];
            $bot->send($chatId,$answer,'HTML',$keyboard);
            $values = $data['message']['from']['id'].','.$data['message']['from']['username'];
            $res = insertData('users', 'tgId,tgName', $values);
            $bot->send('248598993',$res,'HTML');
        };
        switch ($data['message']['text']) {
            case 'Тест':
                $answer = "Работает";
                $bot->send($chatId,$answer,'HTML');
                break;
        }
    }
    if (array_key_exists('callback_query', $data)) {
        $chatId = $data['callback_query']['from']['id'];
        switch ($data['callback_query']['data']) {
            case 'kaz':
                $answer = "Сіз қазақ тілін таңданыңыз";
                $bot->send($chatId,$answer,'HTML');
                break;
            case 'rus':
                $answer = "Вы выбрали русский язык";
                $bot->send($chatId,$answer,'HTML');
                break;
        }
    }

    function insertData($table, $fields, $values)
    {
        $query = "INSERT INTO ".$table." (".$fields.") VALUES (".$values.");";
        $db = Db::getDb();
        return $db->IUDQuery($query,[]);
    }
    function updateData($values)
    {
        $query = "UPDATE ".$table." SET ";
        foreach ($value as $value) {
            $query .= $value[0]." = ".$value[1].", ";
        }
        $query = substr($query, 0, length($query)-2).";";
        $db = Db::getDb();
        return $db->IUDQuery($query,[]);
    }
    function selectData($table, $fields)
    {
        $query = "SELECT ".$fields." FROM ".$table.";";
        $db = Db::getDb();
        return $db->selectQuery($query,[]);
    }

    if (error_get_last()['message'] != '') {
        $bot->send('248598993',error_get_last()['message'].' at line '.error_get_last()['line'],'HTML');
    }
?>
