<?php

require_once("src/model/AuthModel.php");
require_once("src/view/AuthView.php");
require_once("src/view/UserView.php");;
require_once("src/view/RegisterUserView.php");
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
		$this->authView = new AuthView($this->authModel);
		$this->helpers = new Helpers();
		$this->userView = new UserView($this->authModel);
	}

	public function main(){
		$userAgent = $this->helpers->getUserAgent();
		if($this->authView->userIsRemembered() and !$this->authModel->userLoggedIn($userAgent)){
			$loggedInUser = $this->authModel->checkLoginWithCookies($this->authView->getUsernameCookie(), $this->authView->getPasswordCookie(), $userAgent); 

			if($loggedInUser !== null && $loggedInUser->isValid()){
				$this->userView->successfullLogInWithCookiesLoad();						
			} else{
				$this->authView->forgetRememberedUser();
			}
		}

		if($this->authModel->userLoggedIn($userAgent)){
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
			}
			else{
				$this->userView->successfullLogIn();
			}
			return $this->userView->showUser();; 	
		} else if($clientUsername !== ""){
			$this->authView->populateErrorMessage($user);
		}
		return $this->authView->showLogin();
	}

	public function logout(){
		$this->authView->forgetRememberedUser();
		$displayLogoutMessage = $this->authModel->logOut();	
		return $this->authView->showLogin($displayLogoutMessage); 
	}
}