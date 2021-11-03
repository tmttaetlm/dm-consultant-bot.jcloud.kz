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

    public function getTickets($mode)
    {
        switch ($mode) {
            case 'electronics':
                $sql = "SELECT * FROM tickets WHERE branch <> 'Золото' AND branch <> 'Изделие'";
                break;
            case 'jewels':
                $sql = "SELECT * FROM tickets WHERE branch = 'Изделие'";
                break;
            case 'golds':
                $sql = "SELECT * FROM tickets WHERE branch = 'Золото'";
                break;
            default:
                # code...
                break;
        }
        $db = Db::getDb();
        $data = $db->execQuery($sql,[]);
        for ($i=0; $i<count($data); $i++) {
            //1 - активный; 2 - заполнен клиентом, в обработке; 3 - исполненный; 4 - сумма зафиксирована;
            $data[$i]['action'] = '<input type="checkbox" name="ticketToManage" />';
            switch ($data[$i]['status']) {
                case '1':
                    $data[$i]['status'] = 'Новая, заполняется клиентом';
                    break;
                case '2':
                    $data[$i]['status'] = 'Заполнен клиентом, в обработке';
                    break;
                case '3':
                    $data[$i]['status'] = 'Обработан';
                    break;
                case '4':
                    $data[$i]['status'] = 'Зафиксорованная сумма';
                    break;
            }
        }
        return $data;
    }

    public function deleteTickets($params)
    {
        $sql = "DELETE FROM tickets WHERE id IN ".$params['idArr'];
        $db = Db::getDb();
        $data = $db->execQuery($sql,$params);
        return $data;
    }
}