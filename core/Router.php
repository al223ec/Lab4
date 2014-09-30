<?php 

require_once("config/Configs.php"); 

class Router{
	public static $route;

	private $controller;
	private $action;
	private $params;

	public function __construct(){
		self::initRoutes(); 

		$route = isset($_GET['url']) ? $_GET['url'] : '';
		$routeParts = explode('/', $route);
		//Se till att inte otillåtna tecken skickas med i urlen

		$this->controller = $routeParts[0];
		$this->action = isset($routeParts[1]) ? $routeParts[1] : Configs::DefaultAction;

		//Remove the first element from an array
		array_shift($routeParts);
		array_shift($routeParts);
		
		if($this->controller === ""){
			$this->controller = Configs::DefaultController; 
		}
		$this->params = $routeParts; 
		isset($_GET['url']) ? $_GET['url'] = "" : ""; 
	}

	public function getAction(){
		if (empty($this->action)){ 
			$this->action = Configs::DefaultAction;
		}    
		return $this->action;
	}  
	public function getController(){
		//Måste se till att kontrollern alltid stavas Controller, strng format grejor
		if (empty($this->controller)){ 
			$this->controller = Configs::DefaultController;
		}  
	    return $this->controller;
	} 
	
	public function getParams(){
	    return $this->params;  
	}

	public static function initRoutes(){
		self::$route = array(
			"auth" => array(
				"login" =>  \config\Config::AppRoot . "Auth/login", 
				"logout" => \config\Config::AppRoot . "Auth/Logout"
				),
			"register" => array(
				"registerUser" => \config\Config::AppRoot . "RegisterUser/",
				"saveNewUser" => \config\Config::AppRoot .  "RegisterUser/SaveNewUser" 
				)
			); 
	} 
}