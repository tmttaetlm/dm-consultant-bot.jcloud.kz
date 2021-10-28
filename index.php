<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include_once 'db.php';
	$electronics = Db::getDb()->execQuery('SELECT name FROM electronics', []);
    echo array_search('Телевизор', array_column($electronics, 'name'));
?>
