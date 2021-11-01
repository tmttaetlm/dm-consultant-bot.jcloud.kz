<?php
namespace Models;

use Core\Model;
use Components\Db;

class TicketsModel extends Model 
{
    public function __construct($view)
    {
        parent::__construct();
        $this->view = $view;
    }

    public function getOfficesTable()
    {
        $tsql = "SELECT * FROM tickets";
        $db = Db::getDb();
        $data = $db->execQuery($tsql,[]);
        $data = self::addRowNumbers($data);
        return $data;
    }
}