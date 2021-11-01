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
        $tsql = "SELECT tgId, tgName, firstName, lastName FROM users";
        $db = Db::getDb();
        $data = $db->execQuery($tsql,[]);
        $data = self::addRowNumbers($data);
        for ($i=0; $i<count($data); $i++) {
            $data[$i]['name'] = $data[$i]['firstName'].' '. $data[$i]['lastName'];
        }
        return $data;
    }
}