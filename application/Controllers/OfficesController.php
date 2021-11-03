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
        $data['table'] = $this->getOfficesTable();
        $data['wrapper'] = $this->view->generate('offices/list', $data);
        $selectParams = ['id' => 'selectRegion',
                         'items' => $this->model->getRegions()];
        $data['select'] = $this->view->generate('framework/select', $selectParams);
        $data['wrapper'] .= $this->view->generate('offices/manage', $data);
        $data['content'] = $this->view->generate('framework/system', $data);
        $data['user'] = $_SESSION['login'];
        echo $this->view->generate('templateView', $data);
    }

    public function getOfficesTable()
    {
        $data = $this->model->getOfficesTable();
        $title = '';
        $columns = [
            'action' => "\0",
            'city_name' => 'Город',
            'adres' => 'Адрес',
            'phone' => 'Телефон',
            'media_url' => 'Ссылка на картинку'
        ];
        return $this->view->cTable($title,$columns,$data,'','officesList');
    }

    public function actionDeleteOffices()
    {
        return $this->model->deleteOffices($_POST);
    }

    public function actionGetCities()
    {
        $cities = $this->model->getCities($_POST);
        $data = ['id' => 'selectCity',
                 'items' => $this->model->getCities($_POST)];
        if (empty($cities)) $data['disabled'] = 'true';
        echo $this->view->generate('framework/select', $data);
    }

    public function actionAddOffice()
    {
        $this->model->addOffice($_POST);
        $data['table'] = $this->getOfficesTable();
        echo $this->view->generate('offices/list', $data);
    }
}