<?php
/*
 * Class User
 */

namespace Core;
use Components\Db;

class User 
{

    public function __construct() 
    {
        $this->getUserFromDb();
    }
    
    //Get user data from DB, by user iin from session 
    private function getUserFromDb()
    {
        if (isset($_SESSION['login']))
        {
            
        };
    }
    
    //Signing (athorize by AD)
    public function signIn($login,$password)
    {
        $query = "SELECT * FROM (
                    SELECT (SELECT value FROM params WHERE name = 'admin_id') login,
                        (SELECT value FROM params WHERE name = 'admin_pass') pass) admin
                    WHERE login=:login AND pass=:pass;";
        $db = Db::getDb();
        $user = $db->execQuery($query,['login' => $login, 'pass' => $password]);
        if (!empty($user)) {
            $_SESSION['login'] = $user[0]['login'];
            $_SESSION['role'] = $user[0]['role'];
            return true;
        } else {
            return false;
        }
    }
    
    public function isAuth(){
        //Checks is the user authorized?
        if (!isset($_SESSION['user']))
        {
            if (isset($_POST['ajax']))
            {
                exit('Время сессии истекло, <a href="/">выполните вход</a>');
            }

            if(!(($controllerName == 'user') && ($actionName =='signin')))
            {
                $controllerName = 'user';
                $actionName = 'login';    
            }
        }
    }
}
