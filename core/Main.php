<?php

class Main {
	
	public static function dispatch($router){
		$controller = $router->getController();
		$action = $router->getAction();
		$params = $router->getParams();
		$controllerfile = "src/controller/{$controller}.php";

		if (file_exists($controllerfile)){
			require_once($controllerfile);
			$app = new $controller();
			$app->setParams($params);

			if(!method_exists($app, $action)){
				throw new Exception("Controller $controller doesn't have $action funktion");  
			}
			return $app->$action();
		} else {
			throw new Exception("Controller $controller not found");  
		}
	}
}