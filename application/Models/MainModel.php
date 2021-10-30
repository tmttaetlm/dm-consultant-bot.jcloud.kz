<?php

namespace Models;
use Core\Model;
use Components\Db;

class MainModel extends Model {
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getData()
    {
        $data['user'] = $_SESSION['login'];
        return $data;
    }
}
