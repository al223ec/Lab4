<?php

require_once("src/view/RegisterUserView.php");
require_once("src/controller/Controller.php");
require_once("src/model/RegisterUserModel.php");

class RegisterUser extends Controller{
	
	private $registerUserView; 
	private $registerUserModel; 

	public function __construct(){
		$this->registerUserModel = new RegisterUserModel(); 
		$this->registeruserview = new RegisterUserView($this->registerUserModel);
	}
	public function main(){
		return $this->registeruserview->getRegisterForm(); 
	}

	public function saveNewUser(){
		$newUser = $this->registeruserview->getNewUser(); 
		if($newUser !== null && $this->model->saveUser($newUser)){
			$this->loginview->showStatus("Registrering av ny användare lyckades " . $newUser->getUserName());
			return $this->loginview->showLogin();
		}
		else if($newUser !== null){
			return $this->registeruserview->getRegisterForm("Registrering av ny användare misslyckades"); 
		} else {
			return $this->registeruserview->getRegisterForm("Registrering av ny användare misslyckades"); 
		}
	}
}