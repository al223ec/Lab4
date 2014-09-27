<?php

namespace model; 

require_once('src/model/Repository/UserRepository.php'); 
require_once('src/model/User.php'); 

class AuthModel{

	private $sessionLoginData = "LoginModel::LoggedInUser";
	private $sessionUserAgent;
	private $userRepository; 

	public function __construct(){
		$this->userRepository = new UserRepository();
	}

	// Kontrollerar om sessions-varibeln är satt vilket betyder att en användare är inloggad.
	public function userLoggedIn($userAgent){
		if(isset($_SESSION[$this->sessionLoginData]) && $_SESSION[$this->sessionUserAgent] === $userAgent){
			return true;
		}
		else{
			return false;
		}
	}

	// Hämtar vilken användare som är inloggad.
	public function getLoggedInUser(){
		return isset($_SESSION[$this->sessionLoginData]) ? $_SESSION[$this->sessionLoginData] : null;
	}

	// Kontrollerar att inmatat användarnamn och lösenord stämmer vid eventuell inloggning.
	public function checkLogin($clientUsername, $clientPassword, $userAgent){

		//Hämta användare från DB
		$user = $this->userRepository->getUserWithUserName($clientUsername); 
		if($user !== null){
			$user->validate($clientPassword); 
		}
		if($user !== null && $user->isValid()){
			// Sparar ner den inloggad användaren till sessionen.
			$_SESSION[$this->sessionUserAgent] = $userAgent;
			$_SESSION[$this->sessionLoginData] = $user;		
			return true;
		}
		else{
			return false; 

			throw new \Exception("Felaktigt användarnamn och/eller lösenord!");
		}
	}

	// Kontrollerar att inmatat användarnamn och lösenord stämmer vid eventuell inloggning + (med kakor och förfallodatumskontroll).
	public function checkLoginWithCookies($clientUsername, $clientPassword, $userAgent){
		$time = $this->loadCookieTime();
		if($time > time()){
			throw new \Exception("Felaktigt information i kakan!");	
		}
		try{
			$this->checkLogin($clientUsername, $clientPassword, $userAgent); 
		}catch(\Exception $e){
			throw new \Exception("Felaktigt information i kakan!");
		}
	}

	// Hjälpfunktion för att spara till fil.
	public function saveCookieTime($value){
		$this->userRepository->saveCookieTime($userName, $value); 
	}

	// Hjälpfunktion för att ladda från fil.
	public function loadCookieTime(){
		return file_get_contents("CookieTime");
	}


	// Unsettar sessionsvariabeln och dödar sessionen vid eventuell utloggning.
	public function logOut(){
		unset($_SESSION[$this->sessionLoginData]);
		session_destroy();
	}

}