<?php
namespace Models;

use Core\Model;
use Components\Db;

class OfficesModel extends Model 
{
    public function __construct($view)
    {
        parent::__construct();
        $this->view = $view;
    }

    public function getOfficesTable()
    {
        $sql = "SELECT c.name city_name, o.* FROM offices o LEFT JOIN cities c ON c.id = city";
        $db = Db::getDb();
        $data = $db->execQuery($sql,[]);
        for ($i=0; $i<count($data); $i++) {
            $data[$i]['action'] = '<input type="checkbox" name="officeToDelete" />';
            $data[$i]['media_url'] = '<a target="_blank" href='.$data[$i]['media_url'].'>'.$data[$i]['media_url'].'</a>';
        }
        return $data;
    }

    public function deleteOffices($params)
    {
        $sql = "DELETE FROM offices WHERE id IN ".$params['idArr'];
        $db = Db::getDb();
        $data = $db->execQuery($sql,$params);
        return $data;
    }

    public function getRegions()
    {
        $sql = "SELECT id AS value, name AS item FROM regions";
        $db = Db::getDb();
        $data = $db->execQuery($sql,[]);
        return $data;
    }
    public function getCities($param)
    {
        $sql = "SELECT id AS value, name AS item FROM cities WHERE region=:region";
        $db = Db::getDb();
        $data = $db->execQuery($sql,$param);
        return $data;
    }

    public function addOffice($params)
    {
        $params['phone'] = '+'.str_replace(' ', '', $params['phone']);
        $sql = "INSERT INTO offices (city, adres, phone, media_url) VALUES (:city, :adres, :phone, :mediaUrl)";
        $db = Db::getDb();
        $db->execQuery($sql,$params);
    }
}