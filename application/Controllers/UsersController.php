<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\UsersModel;

class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogged();
        $this->model = new UsersModel($this->view);
    }

    public function actionIndex()
    {
        $data['systemTitle'] = 'Пользователи';
        $data['wrapper'] = $this->getUsersTable();
        $data['content'] = $this->view->generate('framework/system',$data);
        $data['user'] = $_SESSION['login'];
        echo $this->view->generate('templateView',$data);
    }

    public function getUsersTable()
    {
        $data = $this->model->getUsersTable();
        $title = '';
        $columns = [
            'num' => '№',
            'tgName' => 'Имя пользователя',
            'tgId' => 'ID телеграмма',
            'name' => 'ФИО',
            'action' => 'Действия'
        ];
        return $this->view->cTable($title,$columns,$data);
    }

    public function actionDeleteUser()
    {
        $this->model->deleteUser($_POST);
    }
}