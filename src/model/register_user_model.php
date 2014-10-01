<?php

namespace model; 
require_once('src/model/repository/user_repository.php'); 
require_once('src/model/user.php'); 
require_once('src/model/model_base.php');
require_once("src/model/session_handler.php");

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