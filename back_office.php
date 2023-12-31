<?php


use \iutnc\backOfficeTouiter\dispatch\Dispatcher;
use \iutnc\backOfficeTouiter\auth\Auth;
use \iutnc\backOfficeTouiter\auth\AuthException;
use \iutnc\backOfficeTouiter\connection\ConnectionFactory;


require_once 'vendor/autoload.php';

ConnectionFactory::setConfig("db.config.ini");
$bdo=ConnectionFactory::makeConnection();

$dispatcher=new Dispatcher();

$dispatcher->run();