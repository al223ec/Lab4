<?php

namespace model; 

class User{

	private $userID; 
	private $userName; 
	private $passwordHash; 
	private $valid; 

	public function __construct($userID = 0, $userName, $passwordHash){
		if($userID === 0){
			$this->passwordHash = $this->createHash($passwordHash); 
		}else{
			$this->passwordHash = $passwordHash; 
		} 
		$this->userName = $userName; 
		$this->userID = $userID; 
		$this->valid = false; 
	} 

	public function validate($password){
		if($crypt($password, $this->passwordHash) === $this->passwordHash ){
			$this->valid = true; 
		}else{
			$this->valid = false; 
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

}