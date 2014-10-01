<?php

namespace model; 

require_once('src/config/config.php');  

class User{

	private $userID; 
	private $userName; 
	private $passwordHash; 
	private $cookieValue; 
	private $cookieTime; 

	private $valid; 

	public function __construct($userID = 0){
		$this->userID = $userID; 
		$this->valid = false; 
	}
	
	public function validate($password){
		if(crypt($password, $this->passwordHash) === $this->passwordHash ){
			$this->valid = true; 
		} else{
			$this->valid = false; 
		}
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
		if(strlen($userName) < \config\Config::UserNameMinLength){
			throw new \Exception("User::setUserName to short user name!"); 
		} 
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

	public function validateByCookieValue($cookieValue){
		$this->valid = $this->cookieValue === $cookieValue && $this->cookieTime > time(); 
		return $this->valid; 

	}
	
	public function __toString(){
		return $this->userName;
    }

    public function setCookieTime($cookieTime){
    	$this->cookieTime = $cookieTime; 
    }

    public function setCookieValue($cookieValue){
    	$this->cookieValue = $cookieValue; 
    }

    public function getCookieTime(){
    	return $this->cookieTime; 
    }
}