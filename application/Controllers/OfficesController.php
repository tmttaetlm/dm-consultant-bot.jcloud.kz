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
            'city'=>'Город',
            'adres'=>'Адрес',
            'phone'=>'Телефон'
        ];
        return $this->view->cTable($title,$columns,$data);
    }
}