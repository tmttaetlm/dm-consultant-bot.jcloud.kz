<?php
namespace Models;

use Core\Model;
use Components\Db;

class UsersModel extends Model 
{
    public function __construct($view)
    {
        parent::__construct();
        $this->view = $view;
    }

    public function getUsersTable()
    {
        $sql = "SELECT tgId, tgName, firstName, lastName FROM users";
        $db = Db::getDb();
        $data = $db->execQuery($sql,[]);
        $data = self::addRowNumbers($data);
        for ($i=0; $i<count($data); $i++) {
            $data[$i]['name'] = $data[$i]['firstName'].' '. $data[$i]['lastName'];
            $data[$i]['action'] = '<button id="deleteUser">Удалить</button>';
        }
        return $data;
    }

    public function deleteUser($params)
    {
        $sql = "DELETE FROM users WHERE tgId=:tgId";
        $db = Db::getDb();
        $data = $db->execQuery($sql,$params);
    }
}