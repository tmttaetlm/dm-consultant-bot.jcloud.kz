<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\ParamsModel;

class ParamsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogged();
        $this->model = new ParamsModel($this->view);
    }

    public function actionIndex()
    {
        $data['systemTitle'] = 'Параметры бота';
        $data['wrapper'] = $this->getBotParams();
        $data['content'] = $this->view->generate('framework/system',$data);
        $data['user'] = $_SESSION['login'];
        echo $this->view->generate('templateView',$data);
    }

    public function getBotParams()
    {
        $params = $this->model->getBotParams();
        $paramArr =[];
        foreach ($params as $value) {
            $paramArr[$value['name']] = $value['value'];
        }
        $data = ['id' => 'selectAdmin',
                 'selected' => $paramArr['admin_id'],
                 'items' => $this->model->getUsersForAdmin()];
        $data['select'] = $this->view->generate('framework/select',$data);
        $data['params'] = $paramArr;
        return $this->view->generate('params/admin',$data);
    }

    public function actionChangeAdmin()
    {
        return $this->model->changeAdmin($_POST);
    }
}