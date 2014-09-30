<?php

require_once("src/model/AuthModel.php");
require_once("src/view/auth/AuthView.php");
require_once("src/view/auth/UserView.php");;
require_once("src/view/register/RegisterUserView.php");
require_once("./common/Helpers.php");
require_once("src/controller/Controller.php");;

class Auth extends Controller{

	private $helpers;
	private $authView;
	private $userView;
	private $authModel;

	public function __construct(){
		// Struktur för att få till MVC.
		$this->authModel = new \model\AuthModel();
		$this->authView = new \view\AuthView($this->authModel);
		$this->helpers = new Helpers();
		$this->userView = new \view\UserView($this->authModel);
	}
	/**
	* Kontroller om användaren är inloggad
	*/
	public function main(){
		$userAgent = $this->helpers->getUserAgent();
		if($this->authView->userIsRemembered() && !$this->authModel->userIsLoggedIn($userAgent)){
			$loggedInUser = $this->authModel->checkLoginWithCookies($this->authView->getUsernameCookie(), $this->authView->getPasswordCookie(), $userAgent); 
			
			if($loggedInUser !== null && $loggedInUser->isValid()){
				$this->userView->successfullLogInWithCookiesLoad();						
			} else{
				$this->authView->forgetRememberedUser();
			}
		}

		if($this->authModel->userIsLoggedIn($userAgent)){
			return $this->userView->showUser();
		}
		return $this->authView->showLogin();
	}

	public function login(){
		$userAgent = $this->helpers->getUserAgent();	
		// Hämtar användarnamn och lösenord.
		$clientUsername = $this->authView->getUsername();
		$clientPassword = $this->authView->getPassword();		
		$user = null; 

		// Kontrollerar om användarnamn och lösenord överensstämmer med sparad data.
		if($clientUsername !== ""){
			$user = $this->authModel->checkLogin($clientUsername, $clientPassword, $userAgent);
		}		
		if($user !== null && $user->isValid()){
			// Om "Håll mig inloggad" är ikryssad, spara i cookies.
			if ($this->authView->RememberMeIsFilled()) {
				$this->authView->saveToCookies($clientUsername, $clientPassword);
				$this->userView->successfullLogInWithCookiesSaved();
			} else {
				$this->userView->successfullLogIn();
			}
			//Lyckad inloggning
			\view\authView::redirect(); 
			exit();
		} else if($clientUsername !== ""){
			$this->authView->populateErrorMessage($user);
		}
		return $this->authView->showLogin();
	}

	public function logout(){
		$this->authView->forgetRememberedUser();
		$this->authModel->logOut();	
		\view\authView::redirect(); 	
		exit();
	}
}