<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\TicketsModel;

class TicketsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogged();
        $this->model = new TicketsModel($this->view);
    }

    public function actionIndex()
    {
        $data['systemTitle'] = 'Заявки';
        $data['electronics'] = $this->getElectronicsTable();
        $data['jewels'] = $this->getJewelsTable();
        $data['golds'] = $this->getGoldsTable();
        $data['wrapper'] = $this->view->generate('tickets/list',$data);
        $data['content'] = $this->view->generate('framework/system',$data);
        $data['user'] = $_SESSION['login'];
        echo $this->view->generate('templateView',$data);
    }

    public function getElectronicsTable()
    {
        $data = $this->model->getTickets('electronics');
        $title = '';
        $columns = [
            'action' => "\0",
            'branch'=>'Тип',
            'model'=>'Модель',
            'number_param'=>'Год',
            'comment'=>'Комментарий',
            'status'=>'Статус заявки'
        ];
        return $this->view->cTable($title,$columns,$data);
    }
    public function getJewelsTable()
    {
        $data = $this->model->getTickets('jewels');
        $title = '';
        $columns = [
            'action' => "\0",
            'branch'=>'Тип',
            'comment'=>'Комментарий',
            'status'=>'Статус заявки'
        ];
        return $this->view->cTable($title,$columns,$data);
    }
    public function getGoldsTable()
    {
        $data = $this->model->getTickets('golds');
        $title = '';
        $columns = [
            'action' => "\0",
            'branch'=>'Тип',
            'model'=>'Проба',
            'number_param'=>'Грамм',
            'price'=>'Сумма',
            'comment'=>'Комментарий',
            'status'=>'Статус заявки'
        ];
        return $this->view->cTable($title,$columns,$data);
    }

    public function actionDeleteTickets()
    {
        $this->model->deleteTickets($_POST);
        switch ($_POST['mode']) {
            case 'golds':
                echo $this->getGoldsTable();
                break;
            case 'jewels':
                echo $this->getJewelsTable();
                break;
            case 'electronics':
                echo $this->getElectronicsTable();
                break;  
        }
        
    }
}