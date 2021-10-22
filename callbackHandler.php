<?php

function callbackHandler($data, $chatId, $text) {
    switch ($text) {
        case 'kaz':
            $answer = "Сіз қазақ тілін таңданыңыз";
            $bot->send($chatId,$answer,'HTML');
            break;
        case 'rus':
            $answer = "Вы выбрали русский язык";
            $bot->send($chatId,$answer,'HTML');
            break;
        default:
            # code...
            break;
    }
}

?>