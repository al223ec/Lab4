<?php

namespace model; 
require_once('src/model/Repository/UserRepository.php'); 
require_once('src/model/User.php'); 
require_once('src/model/ModelBase.php');
require_once("src/model/SessionHandler.php");

class RegisterUserModel extends ModelBase{

	public function saveUser(\model\User $newUser){
		if(!$this->ceckIfUserNameExists($newUser->getUserName())){
			return $this->userRepository->addUser($newUser); 
		}else{
			return false; 
		}
	}
	/**
	* @return True if exists
	*/
	public function ceckIfUserNameExists($userName){
		return $this->userRepository->getUserWithUserName($userName) !== null;
	}
}