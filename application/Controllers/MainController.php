<?php
namespace Controllers;

use Core\Controller;
use Models\MainModel;
use Core\View;

/*
Main system page controller
*/

Class MainController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogged();
        $this->model = new MainModel;
    }
    
    public function actionIndex()
    {
        $data['content'] = $this->getTickets();
        $data['content'] = $data['content']."<div style=\"text-align: center; margin-top: 20px;\"><button id=\"new-ticket\" onclick=\"document.location='/ticket'\">Новая заявка</button></div>";
        $data['user'] = $_SESSION['login'];
        $data['systemTitle'] = 'Все заявки';
        $data['content'] = $this->view->generate('framework/system',$data);
        echo $this->view->generate('templateView',$data);
    }

    public function getTickets()
    {
        $title = ''; 
        $result = $this->model->getTickets();
        $columns = [
            'id' =>'№ заявки',
            'type' => 'Тип заявки',
            'hardware' => 'Оборудование',
            'description'=>'Описание',
            'customer'=> 'Заказчик',
            'datetime' => 'Дата и время заявки',
            'priority' => 'Приоритет',
            'contact' => 'Контакты ответственного лица от заказчика',
            'status' => 'Статус заявки',
            'executor' => 'Ответственный от поставщика'
        ];
        return $this->view->cTable($title,$columns,$result,'ticket-list');
    }
}