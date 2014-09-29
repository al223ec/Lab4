<?php

namespace model; 

require_once('src/model/Repository/UserRepository.php'); 
require_once('src/model/User.php'); 
require_once('src/model/SessionHandler.php'); 

class AuthModel{

	private $sessionLoginData = "AuthModel::LoggedInUser";
	private $sessionUserAgent = "AuthModel::UserAgent";
	
	private $userRepository; 
	private $sessionHandler; 

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

	// Kontrollerar om sessions-varibeln är satt vilket betyder att en användare är inloggad.
	public function userLoggedIn($userAgent){
		return $this->sessionHandler->getSession($this->sessionLoginData) !== "" && $this->sessionHandler->getSession($this->sessionUserAgent) === $userAgent; 
	}

	// Hämtar vilken användare som är inloggad.
	public function getLoggedInUser(){
		return $this->sessionHandler->getSession($this->sessionLoginData) !== "" ? $_SESSION[$this->sessionLoginData] : null;
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
		$elements = array(
			$this->sessionUserAgent => $userAgent,
			$this->sessionLoginData => $user);	
		$this->sessionHandler->setSessionArray($elements); 
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

	//Sparar aktuellt cookievaule och tid till dB
	public function saveCookieValue($value, $cookieTime){
		$userID = $this->getLoggedInUser() !== null ? $this->getLoggedInUser()->getUserID() : 0; 
		$this->userRepository->saveCookieValue($userID, $value, $cookieTime); 
	}

	// Unsettar sessionsvariabeln 
	/**
	* @return True om det finns en session
	*/
	public function logOut(){
		$ret = $this->sessionHandler->sessionKeyIsSet($this->sessionLoginData); 
		if($ret){
			$this->userRepository->resetCookieValues($this->getLoggedInUser()->getUserID()); 
		}
		$this->sessionHandler->unsetSessions(); 
		return $ret; 
	}
}