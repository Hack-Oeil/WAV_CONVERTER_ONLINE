<?php
 
require '../vendor/autoload.php';

$kernel = new Yoop\Kernel();

(new Yoop\Database\Wait)->tryMySQL();

$router = $kernel->getRouter();
$router->load('/app/routes.php');
$router->run($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);