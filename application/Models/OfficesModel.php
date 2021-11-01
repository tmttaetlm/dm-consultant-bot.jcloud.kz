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
        $tsql = "SELECT c.name city, o.* FROM offices o LEFT JOIN cities c ON c.id = city";
        $db = Db::getDb();
        $data = $db->execQuery($tsql,[]);
        $data = self::addRowNumbers($data);
        return $data;
    }
}