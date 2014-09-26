<?php

abstract class Controller{
	
	protected $params; 
	
	public abstract function main();

	public function setParams($params){
		$this->params = $params; 
	}
}