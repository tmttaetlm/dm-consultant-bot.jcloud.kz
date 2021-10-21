<?php
    require_once("vendor/autoload.php"); //Подключаем библиотеку

    $bot = new \TelegramBot\Api\Client('2099253626:AAH8wHq3fRhWpNB3By6R5u2FLBdoLWfdOso'); //Устанавливаем токен, полученный у BotFather
    $result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя
    
    if(!file_exists("registered.trigger")){ 	
	 
        // URl текущей страницы
        $page_url = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        
        $result = $bot->setWebhook($page_url);
        
        if($result){
            file_put_contents("registered.trigger",time()); // создаем файл дабы прекратить повторные регистрации
        }
    }
    
    $bot->command('start', function ($message) use ($bot) {
        $answer = 'Привет!';
        $bot->sendMessage($message->getChat()->getId(), $answer);
    });
?>
