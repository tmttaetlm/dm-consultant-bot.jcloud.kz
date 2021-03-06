<?php
namespace Components;

use PDO;

/* DB
 * Работа с базой данных
 */

class Db {
    private static $db;
    private $link;

    private function __construct(){
        
        //Подготовка параметров для подключения к базе данных
        $host = '127.0.0.1';
        $dbname = 'dmdb';
        $user = 'dm_admin';
        $password = 'Qwerty1!'; 
        
        $dsn = "mysql:host=$host; dbname=$dbname;charset=utf8";
        $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
               ];

        //Подключаемся к БД
        try 
        {
            $this->link = new PDO($dsn, $user, $password, $opt);
        } 
        catch (PDOException $e) 
        {
            echo 'Data base problem:<br>';
            echo $e->getMessage(); 
            die();
        }
        
    }

    //Статический метод получения соединения (Синглтон)
    public static function getDb() {
        if (is_null(self::$db)){
            self::$db = new self();
        }
        return self::$db;
    }

    //Запрос на выборку из БД
    public function execQuery($query,array $params = null) {
        try {
            $stmt= $this->link->prepare($query);
            if ($params) 
            {
                foreach ($params as $key => $value) 
                {
                    $bindKey = ':' . $key;
                    $stmt->bindValue($bindKey, $params[$key]);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo 'Data base problem:<br>';
            return $e->getMessage(); 
            die();            
        }
    }
    
}