<?php
namespace Models;

use Core\Model;
use Components\Db;

class JewelsModel extends Model 
{
    public function __construct($view)
    {
        parent::__construct();
        $this->view = $view;
    }

    public function getJewelsTable()
    {
        $sql = "SELECT name FROM jewels";
        $db = Db::getDb();
        $data = $db->execQuery($sql,[]);
        for ($i=0; $i<count($data); $i++) {
            $data[$i]['action'] = '<input type="checkbox" name="officeToDelete" />';
        }
        return $data;
    }

    public function deleteJewels($params)
    {
        $sql = "DELETE FROM jewels WHERE id IN ".$params['idArr'];
        $db = Db::getDb();
        $data = $db->execQuery($sql,$params);
        return $data;
    }

    public function addJewel($params)
    {
        $sql = "INSERT INTO jewels (name) VALUES (:name)";
        $db = Db::getDb();
        $db->execQuery($sql,$params);
    }
}