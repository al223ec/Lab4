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
			try {
				// Hämtar de lagrade kakorna, kontrollerar och jämför dem med sparad data.
				$this->authModel->checkLoginWithCookies($this->authView->getUsernameCookie(), $this->authView->getPasswordCookie(), $userAgent);
				$this->userView->successfullLogInWithCookiesLoad();						
			} catch (Exception $e) {
				$this->authView->forgetRememberedUser();
				$this->authView->showStatus($e->getMessage());
			}
		}

		if($this->authModel->userLoggedIn($userAgent)){
			return $this->userView->showUser();
		}
		return $this->authView->showLogin();
	}

	public function login(){
		$userAgent = $this->helpers->getUserAgent();	
		try {
			// Hämtar användarnamn och lösenord.
			$clientUsername = $this->authView->getUsername();
			$clientPassword = $this->authView->getPassword();		
			
			// Kontrollerar om användarnamn och lösenord överensstämmer med sparad data.
			$this->authModel->checkLogin($clientUsername, $clientPassword, $userAgent);

			// Om "Håll mig inloggad" är ikryssad, spara i cookies.
			if ($this->authView->RememberMeIsFilled()) {
				$this->authView->saveToCookies($clientUsername, $clientPassword);
				$this->userview->successfullLogInWithCookiesSaved();
			}
			else{
				$this->userview->successfullLogIn();	
			}
		// Felmeddelande vid eventuella fel i try-satsen.
		} catch (Exception $e) {
			$this->authView->showStatus($e->getMessage());
			return $this->authView->showLogin();	
		}
		return $this->userview->showUser();; 
	}

	public function logout(){
		$this->authView->forgetRememberedUser();
		$this->authModel->logOut();	
		$this->authView->successfullLogOut();
		return $this->authView->showLogin(); 
	}
}