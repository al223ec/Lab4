<?php

namespace view; 

require_once("src/view/cookie_service.php");
require_once("src/config/config.php");
require_once("src/view/view_base.php");

class AuthView extends ViewBase{
	
	private $cookieUsername;						// Instans av CookieStorage för att lagra användarnamn.
	private $cookiePassword;						// Instans av CookieStorage för att lagra lösenord.
	private $username = "LoginView::Username";		// Användarnamnets kakas namn.
	private $password = "LoginView::Password";		// Lösenordets kakas namn.
	private $errorMessage;								// Privat variabel för att visa fel/rättmeddelanden.

	//Min uppdatering ta bort strängberoende
	private static $RememberMe = "LoginView::checked"; 

	public function __construct(\model\AuthModel $model){
		parent::__construct($model);
		$this->cookieUsername = new \CookieService();
		$this->cookiePassword = new \CookieService();
	}

	public function populateErrorMessage($user){
		if($user === null){ 
			$this->errorMessage = "Felaktigt användarnamn"; 	
		} else if(!$user->isValid()){
			$this->errorMessage = "Felaktigt lösenord"; 
		}
	}
	// Kontrollerar användare checkat i Håll mig inloggad.
	public function RememberMeIsFilled(){
		return isset($_POST[self::$RememberMe]); 
	}

	// Funktion för att hämta sparade kakor.
	public function userIsRemembered(){
		return $this->cookieUsername->loadCookie($this->username) && $this->cookiePassword->loadCookie($this->password);
	}

	// Funktion för att spara kakor (och spara ner förfallotid).
	public function saveToCookies($username){
		$this->cookieUsername->saveCookie($this->username, $username);
		$this->model->saveCookieValue($this->cookiePassword->saveCookie($this->password), $this->cookiePassword->getCookieExpiration());
	}

	// Funktion för att radera sparade kakor.
	public function forgetRememberedUser(){
		$this->cookieUsername->removeCookie($this->username);
		$this->cookiePassword->removeCookie($this->password);
	}

	// Hämtar användarnamn från kakan.
	public function getUsernameCookie(){
		return $this->cookieUsername->loadCookie($this->username);
	}

	// Hämtar lösenord från kakan.
	public function getPasswordCookie(){
		return $this->cookiePassword->loadCookie($this->password);
	}

	// Hämtar Användarnamnet vid rätt input.
	public function getUsername(){
		$ret = $this->getCleanInput($this->username); 
		if($ret === ""){
			$this->errorMessage = "Användarnamn saknas!";
			return ""; 
		}
		return $ret; 
	}

	// Hämtar lösenordet vid rätt input.
	public function getPassword(){
		$ret = $this->getCleanInput($this->password); 
		if($ret === ""){
			if(!$this->errorMessage){
				$this->errorMessage = "Lösenord saknas!";	
			}else{
				$this->errorMessage .= "Lösenord saknas!";		
			}
		}
		return $ret; 
	}

	public function setLogOutMessage(){
		$this->model->setSessionReadOnceMessage("Du har nu loggat ut!");
	}

	public function setFaultyCookiesMessage(){
		$this->model->setSessionReadOnceMessage("Felaktig information i kakan!");
	}
	// Slutlig presentation av utdata.
	public function showLogin(){
		$ret = 	"<a href='" . \router::$route['register']['register'] . "'> Registrera ny användare </a>";  
		$ret .= "<h2>Ej inloggad!</h2>";

		$ret .= "
				<fieldset>
				<legend>Logga in här!</legend>";

		$ret .= $this->getMessage();

		$ret .= "<p>" . $this->errorMessage . "</p>";

		$ret .= "
				<form action='". \router::$route['auth']['login'] . "' method='post' >";
		
		// Om det inte finns något inmatat användarnamn så visa tom input.
		// Annars visa det tidigare inmatade användarnamnet i input.
		//kan innehålla ett användarnamn, jävligt osnygg lösning.
		$newlyAddedUserName = $this->getNewlyAddedUserName();
		
		$uservalue = empty($_POST[$this->username]) ? "" : $_POST[$this->username];

		$uservalue = $newlyAddedUserName !== "" ? $newlyAddedUserName : $uservalue; 

		$ret .= "Användarnamn: <input type='text' name='$this->username' value='$uservalue'>";
	
		$ret .= "
					Lösenord: <input type='password' name='$this->password'>
					Håll mig inloggad: <input type='checkbox' name='LoginView::checked'>
					<input type='submit' value='Logga in' name='LoginView::login'>
				</form>
				</fieldset>
				";

		return $ret;
	}
}