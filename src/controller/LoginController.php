<?php

require_once("src/model/LoginModel.php");
require_once("src/view/LoginView.php");
require_once("src/view/UserView.php");;
require_once("src/view/RegisterUserView.php");
require_once("./common/Helpers.php");


class LoginController{

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

	public function doControll(){
		// Hanterar indata.

		// Hämtar information som webbläsaren användaren sitter i.
		$userAgent = $this->helpers->getUserAgent();

		// Om det finns kakor lagrade och användaren inte redan är inloggad.
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

		// Om användaren redan är inloggad.
		if($this->userview->didUserPressLogout()){
			$this->loginview->forgetRememberedUser();
			$this->model->logOut();
			$this->loginview->successfullLogOut();
		}

		// Om användaren inte är inloggad och tryckt på Logga in.
		if($this->loginview->didUserPressLogin()){

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
			}
		}


		// Generar utdata.

		// Om inloggningen lyckades visa användarfönstret.
			if($this->model->userLoggedIn($userAgent)){
				return $this->userview->showUser();
			}

			//Min kod
			if($this->registeruserview->didUserPressRegister()){
				return $this->registeruserview->getRegisterForm(); 
			}

			if($this->registeruserview->didUserPressSaveNewUser()){
				$newUser = $this->registeruserview->getNewUser(); 

				if($newUser !== null && $this->model->saveUser($newUser)){
					$this->loginview->showStatus("Registrering av ny användare lyckades " . $newUser->getUserName());
					return $this->loginview->showLogin();
				}
				else if($newUser !== null){
					return $this->registeruserview->getRegisterForm("Registrering av ny användare misslyckades"); 
				} else {
					return $this->registeruserview->getRegisterForm("Registrering av ny användare misslyckades"); 
				}

		}
		// Annars visa inloggningsfönstret.
		return $this->registeruserview->getRegisterLink() . $this->loginview->showLogin();
	}
}