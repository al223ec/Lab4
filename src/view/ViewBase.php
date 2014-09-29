<?php

namespace view;

class ViewBase{
	
	protected $model;

	public function __construct($model){
		$this->model = $model;	
	}

	public static function redirect(){
		header("Location: " . \config\Config::AppRoot);
	}
	
	protected function getMessage(){
		return "<p>" . $this->model->getSessionMessage() . "</p>"; 
	}
	protected function setMessage($message){
		$this->model->setSessionMessage($message); 
	}

	protected function getInput($inputName){
		return isset($_POST[$inputName]) ? $_POST[$inputName] : "";
	}
	protected function getCleanInput($inputName) {
		return isset($_POST[$inputName]) ? $this->sanitize($_POST[$inputName]) : "";
	}
	protected function sanitize($input) {
        $temp = trim($input);
        return filter_var($temp, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    }
	
}