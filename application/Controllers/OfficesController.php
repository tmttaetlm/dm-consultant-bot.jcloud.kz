<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\OfficesModel;

class OfficesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogged();
        $this->model = new OfficesModel($this->view);
    }

    public function actionIndex()
    {
        $data['systemTitle'] = 'Отделения';
        $data['wrapper'] = $this->getOfficesTable();
        $data['wrapper'] .= $this->view->generate('offices/manage',$data);
        $data['content'] = $this->view->generate('framework/system',$data);
        $data['user'] = $_SESSION['login'];
        echo $this->view->generate('templateView',$data);
    }

    public function getOfficesTable()
    {
        $data = $this->model->getOfficesTable();
        $title = '';
        $columns = [
            'action' => "\0",
            'cityName' => 'Город',
            'adres' => 'Адрес',
            'phone' => 'Телефон'
        ];
        return $this->view->cTable($title,$columns,$data);
    }

    public function actionDeleteOffices()
    {
        return $this->model->deleteOffices($_POST);
    }
}