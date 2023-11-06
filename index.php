<?php
use \iutnc\touiter\dispatch as Dispatch;

use \iutnc\touiter\connection as Connection;


require_once 'vendor/autoload.php';

Connection\ConnectionFactory::setConfig("db.config.ini");
$bdo=Connection\ConnectionFactory::makeConnection();

$dispatcher=new Dispatch\Dispatcher();

$dispatcher->run();