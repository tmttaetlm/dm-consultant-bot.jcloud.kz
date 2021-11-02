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
        $tsql = "SELECT c.name cityName, o.* FROM offices o LEFT JOIN cities c ON c.id = city";
        $db = Db::getDb();
        $data = $db->execQuery($tsql,[]);
        for ($i=0; $i<count($data); $i++) {
            $data[$i]['action'] = '<input type="checkbox" name="officeToDelete" />';
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
}