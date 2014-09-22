<?php

session_start();

require_once("common/HTMLView.php");
require_once("src/controller/LoginController.php");
require_once("src/model/LoginModel.php");
require_once("src/view/LoginView.php");


$doC = new LoginController();
$htmlBody = $doC->doControll();

$view = new  HTMLView();
$view->echoHTML($htmlBody);