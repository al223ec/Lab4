<?php

require_once("src/view/register/register_user_view.php");
require_once("src/controller/controller.php");
require_once("src/model/register_user_model.php");

//Måste vara mer konsekvent i min namngivning
class Registeruser extends Controller{
	
	private $registerUserView; 
	private $registerUserModel;

	public function __construct(){
		$this->registerUserModel = new \model\RegisterUserModel(); 
		$this->registerUserView = new \view\RegisterUserView($this->registerUserModel);
	}
	public function main(){
		return $this->registerUserView->getRegisterForm(); 
	}

	public function saveNewUser(){
		$newUser = $this->getNewUser(); 

		if($newUser !== null && $this->registerUserModel->saveUser($newUser)){
			$this->registerUserView->setSuccessMessage($newUser->getUserName()); 
			\view\ViewBase::redirect();
			return; 
		}
		$this->registerUserView->setFailMessage(); 
		return $this->registerUserView->getRegisterForm(); 
	}
	//Flytta denna till vyn?? 
	private function getNewUser(){
		$userName = $this->registerUserView->getUserName(); 
		$password = $this->registerUserView->getPassword(); 

		if($userName !== "" && $password !== ""){
			$newUser = new \model\User(); 
			$newUser->setUserName($userName); 
			$newUser->setPassword($password);  
			return $newUser; 
		} 
		return null; 
	}
}