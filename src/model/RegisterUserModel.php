<?php

require_once('src/model/Repository/UserRepository.php'); 
require_once('src/model/User.php'); 

class RegisterUserModel{

	private $userRepository; 
	public function __construct(){
		$this->userRepository = new model\UserRepository(); //Denna bör kanske laddas via singelton mönstret?? 
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
		return $this->userRepository->getUserWithUserName($userName) === null;
	}
}