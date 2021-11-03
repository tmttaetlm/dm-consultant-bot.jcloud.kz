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
        $sql = "SELECT id, tgId, tgName, firstName, lastName FROM users";
        $db = Db::getDb();
        $data = $db->execQuery($sql,[]);
        for ($i=0; $i<count($data); $i++) {
            $data[$i]['name'] = $data[$i]['firstName'].' '. $data[$i]['lastName'];
            $data[$i]['action'] = '<input type="checkbox" name="usersToDelete" />';
        }
        return $data;
    }

    public function deleteUsers($params)
    {
        $sql = "DELETE FROM users WHERE id IN ".$params['idArr'];
        $db = Db::getDb();
        $data = $db->execQuery($sql,$params);
        return $data;
    }
}