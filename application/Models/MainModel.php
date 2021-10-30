<?php

namespace Models;
use Core\Model;
use Components\Db;

class MainModel extends Model {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getTickets()
    {
        $query = "SELECT * FROM sd_tickets;";
        $db = Db::getDb();
        $data = $db->execQuery($query,[]);
        $data = $this->addRowNumbers($data);
        for ($i=0; $i<count($data); $i++) {
            switch ($data[$i]['type']) {
                case 'service': $data[$i]['type'] = 'Обслуживание'; break;
                case 'repair': $data[$i]['type'] = 'Ремонт'; break;
                case 'accident': $data[$i]['type'] = 'Инцидент'; break;
                case 'other': $data[$i]['type'] = 'Другое'; break;
            }
            switch ($data[$i]['hardware']) {
                case 'video': $data[$i]['hardware'] = 'Видеонаблюдение'; break;
                case 'ops': $data[$i]['hardware'] = 'ОПС'; break;
                case 'skud': $data[$i]['hardware'] = 'СКУД'; break;
                case 'other': $data[$i]['hardware'] = 'Другое'; break;
            }
            switch ($data[$i]['priority']) {
                case '1': $data[$i]['priority'] = 'Низкий'; break;
                case '2': $data[$i]['priority'] = 'Средний'; break;
                case '3': $data[$i]['priority'] = 'Высокий'; break;
            }
            switch ($data[$i]['status']) {
                case 'send':
                    if ($_SESSION['role'] == 'admin') {
                        $data[$i]['status'] = '<select id="status">
                                                <option value="send" selected>Отправлена</option>
                                                <option value="received">Получена</option>
                                                <option value="work">На исполнении</option>
                                                <option value="done">Исполнено</option>
                                              </select>';
                    } else { $data[$i]['status'] = 'Отправлена'; };
                    break;
                case 'received':
                    if ($_SESSION['role'] == 'admin') {
                        $data[$i]['status'] = '<select id="status">
                                                <option value="send">Отправлена</option>
                                                <option value="received" selected>Получена</option>
                                                <option value="work">На исполнении</option>
                                                <option value="done">Исполнено</option>
                                              </select>';
                    } else { $data[$i]['status'] = 'Получена'; };
                    break;
                case 'work':
                    if ($_SESSION['role'] == 'admin') {
                        $data[$i]['status'] = '<select id="status">
                                                <option value="send">Отправлена</option>
                                                <option value="received">Получена</option>
                                                <option value="work" selected>На исполнении</option>
                                                <option value="done">Исполнено</option>
                                              </select>';
                    } else { $data[$i]['status'] = 'На исполнении'; };
                    break;
                case 'done':
                    if ($_SESSION['role'] == 'admin') {
                        $data[$i]['status'] = '<select id="status">
                                                <option value="send">Отправлена</option>
                                                <option value="received">Получена</option>
                                                <option value="work">На исполнении</option>
                                                <option value="done" selected>Исполнено</option>
                                              </select>';
                    } else { $data[$i]['status'] = 'Исполнено'; };
                    break;
            }
            $year = substr($data[$i]['datetime'],0,4);
            $month = substr($data[$i]['datetime'],5,2);
            $day = substr($data[$i]['datetime'],8,2);
            $time = substr($data[$i]['datetime'],11,5);
            $data[$i]['datetime'] = $day.'.'.$month.'.'.$year.' '.$time;
        }
        return $data;
    }
}
