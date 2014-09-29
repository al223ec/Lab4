<?php

namespace model; 
require_once('src/model/Repository/UserRepository.php'); 
require_once('src/model/User.php'); 
require_once("src/model/SessionHandler.php");

class RegisterUserModel{

	private $userRepository; 
	private $sessionMessage = "AuthModel::Message"; 
	
	public function __construct(){
		$this->userRepository = new UserRepository();
		$this->sessionHandler = new SessionHandler(); 
	}
	
	public function setSessionMessage($message){
		$this->sessionHandler->setSessionReadOnceMessage($message); 
	}
	public function getSessionMessage(){
		return $this->sessionHandler->getSessionReadOnceMessage(); 
	}

	public function saveUser(\model\User $newUser){
		if(!$this->ceckIfUserNameExists($newUser->getUserName())){
			return $this->userRepository->addUser($newUser); 
		}else{
			return false; 
		}
	}
	/**
	*True if exists
	*/
	public function ceckIfUserNameExists($userName){
		return $this->userRepository->getUserWithUserName($userName) !== null;
	}
}