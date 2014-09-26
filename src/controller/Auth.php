<?php

require_once("src/model/LoginModel.php");
require_once("src/view/LoginView.php");
require_once("src/view/UserView.php");;
require_once("src/view/RegisterUserView.php");
require_once("./common/Helpers.php");
require_once("src/controller/Controller.php");;

class Auth extends Controller{

	private $helpers;
	private $loginview;
	private $userview;
	private $model;
	private $registeruserview; 

	public function __construct(){

		// Struktur för att få till MVC.
		$this->model = new LoginModel();
		$this->loginview = new LoginView($this->model);
		$this->userview = new UserView($this->model);
		$this->registeruserview = new RegisterUserView($this->model); 
		$this->helpers = new Helpers();
	}

	public function main(){
		$userAgent = $this->helpers->getUserAgent();
		if($this->loginview->userIsRemembered() and !$this->model->userLoggedIn($userAgent)){
			try {
				// Hämtar de lagrade kakorna, kontrollerar och jämför dem med sparad data.
				$this->model->checkLoginWithCookies($this->loginview->getUsernameCookie(), $this->loginview->getPasswordCookie(), $userAgent);
				$this->userview->successfullLogInWithCookiesLoad();						
			} catch (Exception $e) {
				$this->loginview->forgetRememberedUser();
				$this->loginview->showStatus($e->getMessage());
			}
		}
		if($this->model->userLoggedIn($userAgent)){
			return $this->userview->showUser();
		}
		return $this->registeruserview->getRegisterLink() . $this->loginview->showLogin();
	}

	public function login(){
		$userAgent = $this->helpers->getUserAgent();	
		try {
			// Hämtar användarnamn och lösenord.
			$clientUsername = $this->loginview->getUsername();
			$clientPassword = $this->loginview->getPassword();		
				
				// Kontrollerar om användarnamn och lösenord överensstämmer med sparad data.
				$this->model->checkLogin($clientUsername, $clientPassword, $userAgent);

				// Om "Håll mig inloggad" är ikryssad, spara i cookies.
				if ($this->loginview->RememberMeIsFilled()) {
					$this->loginview->saveToCookies($clientUsername, $clientPassword);
					$this->userview->successfullLogInWithCookiesSaved();
				}
				else{
					$this->userview->successfullLogIn();	

				}

		// Felmeddelande vid eventuella fel i try-satsen.
		} catch (Exception $e) {
			$this->loginview->showStatus($e->getMessage());
			return $this->loginview->showLogin();	
		}
		return $this->userview->showUser();; 
	}

	public function logout(){
		$this->loginview->forgetRememberedUser();
		$this->model->logOut();
		$this->loginview->successfullLogOut();
	}
	public function doControll(){
		// Hanterar indata.

		// Hämtar information som webbläsaren användaren sitter i.

		// Om det finns kakor lagrade och användaren inte redan är inloggad.


		// Om användaren redan är inloggad.


		// Om användaren inte är inloggad och tryckt på Logga in.
		// Generar utdata.

		// Om inloggningen lyckades visa användarfönstret.


			//Min kod
			if($this->registeruserview->didUserPressRegister()){

			}

			if($this->registeruserview->didUserPressSaveNewUser()){
	

		}
		// Annars visa inloggningsfönstret.
		return $this->registeruserview->getRegisterLink() . $this->loginview->showLogin();
	}
}