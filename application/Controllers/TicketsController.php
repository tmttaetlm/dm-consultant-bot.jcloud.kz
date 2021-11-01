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
        $data['wrapper'] = $this->getOfficesTable();
        $data['content'] = $this->view->generate('framework/system',$data);
        $data['user'] = $_SESSION['login'];
        echo $this->view->generate('templateView',$data);
    }

    public function getOfficesTable()
    {
        $data = $this->model->getOfficesTable();
        $title = '';
        $columns = [
            'num'=>'№',
            'branch'=>'Тип',
            'model'=>'Модель/Проба',
            'number_param'=>'Год/Грамм',
            'comment'=>'Комментарий',
        ];
        return $this->view->cTable($title,$columns,$data);
    }
}