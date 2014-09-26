<?php

namespace model; 

require_once('src/config/Config.php');  

class User{

	private $userID; 
	private $userName; 
	private $passwordHash; 
	private $valid; 
	private $errors; 

	public function __construct($userID = 0){
		$this->userID = $userID;  
		$this->errors = array(); 
		$this->valid = false; 
	}
	public function validate($password){
		if(crypt($password, $this->passwordHash) === $this->passwordHash ){
			$this->valid = true; 
		} else{
			$this->valid = false; 
		}
		var_dump($password === $this->passwordHash); 
		if($password === $this->passwordHash){
			$this->valid = true; 
		}
		return $this->valid; 
	}

	private function createHash($password){
		$cost = 10;
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		return crypt($password, $salt);
	}

	public function isValid(){
		return $this->valid; 
	}
	public function getUserID(){
		return $this->userID; 
	}
	public function getUserName(){
		return $this->userName; 
	}
	public function getPasswordHash(){
		return $this->passwordHash; 
	}
	public function setUserName($userName){
		$this->userName = $userName;
	}
	
	public function setPasswordHash($passwordHash){
		$this->passwordHash = $passwordHash; 
	}
	public function setPassword($password){
		if(strlen($password) < \config\Config::PasswordMinLength){
			throw new \Exception("User::setPassword to short password!"); 
		} 
		if($this->userID === 0){
			$this->passwordHash = $this->createHash($password); 

		}else {
			throw new Exception("User::setPassword can only be used on new User objects!"); 
		}
	}

	public function getErrors(){

	}
	public function __toString(){
		return $this->userName;
    }
}