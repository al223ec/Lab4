<?php

session_start();

require_once("common/HTMLView.php");
require_once("core/Main.php"); 
require_once("core/Router.php"); 

$router = new Router();  
$view = new  HTMLView();
$view->echoHTML(Main::dispatch($router));