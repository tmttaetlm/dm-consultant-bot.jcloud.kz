<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\JewelsModel;

class JewelsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogged();
        $this->model = new JewelsModel($this->view);
    }

    public function actionIndex()
    {
        $data['systemTitle'] = 'Изделия';
        $data['table'] = $this->getJewelsTable();
        $data['wrapper'] = $this->view->generate('jewels/list', $data);
        $data['wrapper'] .= $this->view->generate('jewels/manage', $data);
        $data['content'] = $this->view->generate('framework/system', $data);
        $data['user'] = $_SESSION['login'];
        echo $this->view->generate('templateView', $data);
    }

    public function getJewelsTable()
    {
        $data = $this->model->getJewelsTable();
        $title = '';
        $columns = [
            'action' => "\0",
            'name' => 'Наименования изделия'
        ];
        return $this->view->cTable($title,$columns,$data,'','jewelsList');
    }

    public function actionDeleteJewels()
    {
        return $this->model->deleteJewels($_POST);
    }

    public function actionAddJewel()
    {
        $this->model->addJewel($_POST);
        $data['table'] = $this->getJewelsTable();
        echo $this->view->generate('jewels/list', $data);
    }
}