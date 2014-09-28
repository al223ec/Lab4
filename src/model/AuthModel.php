<?php

namespace model; 

require_once('src/model/Repository/UserRepository.php'); 
require_once('src/model/User.php'); 

class AuthModel{

	private $sessionLoginData = "LoginModel::LoggedInUser";
	private $sessionUserAgent = "LoginModel::UserAgent";
	private $userRepository; 

	public function __construct(){
		$this->userRepository = new UserRepository();
	}

	// Kontrollerar om sessions-varibeln är satt vilket betyder att en användare är inloggad.
	public function userLoggedIn($userAgent){
		return isset($_SESSION[$this->sessionLoginData]) && $_SESSION[$this->sessionUserAgent] === $userAgent;
	}

	// Hämtar vilken användare som är inloggad.
	public function getLoggedInUser(){
		return isset($_SESSION[$this->sessionLoginData]) ? $_SESSION[$this->sessionLoginData] : null;
	}

	// Kontrollerar att inmatat användarnamn och lösenord stämmer vid eventuell inloggning.
	/** 
	* @return User or null 
	*/
	public function checkLogin($clientUsername, $clientPassword, $userAgent){
		//Hämta användare från DB
		$user = $this->userRepository->getUserWithUserName($clientUsername); 
		if($user !== null){
			$user->validate($clientPassword); 
		}
		if($user !== null && $user->isValid()){
			// Sparar ner den inloggad användaren till sessionen.
			$this->saveUserToSession($user, $userAgent); 
	
		}
		return $user; 	
	}

	private function saveUserToSession($user, $userAgent){
		$_SESSION[$this->sessionUserAgent] = $userAgent;
		$_SESSION[$this->sessionLoginData] = $user;	

	}

	// Kontrollerar att inmatat användarnamn och lösenord stämmer vid eventuell inloggning + (med kakor och förfallodatumskontroll).
	public function checkLoginWithCookies($clientUsername, $cookieValue, $userAgent){
		$user = $this->userRepository->getUserWithUserName($clientUsername); 
		$user->validateByCookieValue($cookieValue); 
		
		if($user->getCookieTime() < time()){
			return null; 
		}
		if($user->isValid()){
			$this->saveUserToSession($user, $userAgent); 
			return $user; 
		}
		return null; 
	}

	// Hjälpfunktion för att spara till fil.
	public function saveCookieValue($value, $cookieTime){
		$userID = $this->getLoggedInUser() !== null ? $this->getLoggedInUser()->getUserID() : 0; 
		$this->userRepository->saveCookieValue($userID, $value, $cookieTime); 
	}

	// Unsettar sessionsvariabeln 
	/**
	* @return True om det finns en session
	*/
	public function logOut(){
		$ret = isset($_SESSION[$this->sessionLoginData]); 
		if($ret){
			$this->userRepository->resetCookieValues($this->getLoggedInUser()->getUserID()); 
		}
		unset($_SESSION[$this->sessionLoginData]);
		unset($_SESSION[$this->sessionUserAgent]);

		return $ret; 
	}

}