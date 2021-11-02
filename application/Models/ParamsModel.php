<?php
namespace Models;

use Core\Model;
use Components\Db;

class ParamsModel extends Model 
{
    public function __construct($view)
    {
        parent::__construct();
        $this->view = $view;
    }

    public function getBotParams()
    {
        $sql = "SELECT * FROM params";
        $db = Db::getDb();
        $data = $db->execQuery($sql,[]);
        return $data;
    }

    public function getUsersForAdmin()
    {
        $sql = "SELECT tgId AS value, concat(tgId,' | ',firstName,' ',lastName) AS item FROM users";
        $db = Db::getDb();
        $data = $db->execQuery($sql,[]);
        return $data;
    }

    public function changeAdmin($params)
    {
        $sql = "UPDATE params SET value=:id WHERE name='admin_id'";
        $db = Db::getDb();
        $data = $db->execQuery($sql,['id' => $params['id']]);
        $sql = "UPDATE params SET value=:nik WHERE name='admin_name'";
        $db = Db::getDb();
        $data = $db->execQuery($sql,['nik' => $params['name']]);
        return $data;
    }
}