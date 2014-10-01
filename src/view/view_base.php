<?php

namespace view;

abstract class ViewBase{
	
	protected $model; 
	
	private $sessionKey = "ViewBase::SessionKey"; 

	public function __construct($model){
		$this->model = $model;	
	}

	public static function redirect(){
		header("Location: " . \config\Config::AppRoot);
	}
	
	protected function getMessage(){
		return "<p>" . $this->model->getSessionReadOnceMessage() . "</p>"; 
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

    protected function setSession($message){
    	$this->model->setSessionMessage($this->sessionKey, $message); 
    }
/*
    private $userNameSessionKey = "ViewBase::NewUserNameSessionKey"; 
    //"Fula" funktioner
    protected function saveNewlyAddedUserName($userName){
    	$this->model->setSessionMessage($this->userNameSessionKey, $userName); 
    } */

    protected function getNewlyAddedUserName(){
    	return $this->model->readAndRemoveSessionMessage($this->sessionKey); 
    }
}