<?php

require_once("CookieService.php");
require_once("src/config/Config.php");

class AuthView{
	
	private $model;
	private $cookieUsername;						// Instans av CookieStorage för att lagra användarnamn.
	private $cookiePassword;						// Instans av CookieStorage för att lagra lösenord.
	private $username = "LoginView::Username";		// Användarnamnets kakas namn.
	private $password = "LoginView::Password";		// Lösenordets kakas namn.
	private $message;								// Privat variabel för att visa fel/rättmeddelanden.

	//Min uppdatering ta bort strängberoende
	const ActionLogin = "Auth/login"; 
	const RememberMe = "LoginView::checked"; 

	public function __construct(\model\AuthModel $model){
		// Struktur för MVC.
		$this->model = $model;
		$this->cookieUsername = new CookieService();
		$this->cookiePassword = new CookieService();
	}

	public function populateErrorMessage($user){
		if($user === null){ 
			$this->message = "Felaktigt användarnamn"; 	
		} else if(!$user->isValid()){
			$this->message = "Felaktigt lösenord"; 
		}
	}
	// Kontrollerar användare checkat i Håll mig inloggad.
	public function RememberMeIsFilled(){
		return isset($_POST[self::RememberMe]); 
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
		if (empty($_POST[$this->username])) {
			$this->message = "Användarnamn saknas!";
			return ""; 
		}
		else {
			return $_POST[$this->username];	
		}
	}

	// Hämtar lösenordet vid rätt input.
	public function getPassword(){
		if (empty($_POST[$this->password])) {
			if(!$this->message){
				$this->message = "Lösenord saknas!";	
			}else{
				$this->message .= " Lösenord saknas!";		
			}
			return ""; 
		}
		else {
			return $_POST[$this->password];	
		}
	}

	// Datum och tid-funktion. (Kan brytas ut till en hjälpfunktion.)
	public function getDateTime(){
		setlocale(LC_ALL, 'sv_SE');
		$weekday = ucfirst(utf8_encode(strftime("%A,")));
		$date = strftime("den %d");
		$month = strftime("%B");
		$year = strftime("år %Y.");
		$time = strftime("Klockan är [%H:%M:%S].");
		return "$weekday $date $month  $year  $time";	
	}

	// Visar fel/rättmeddelanden.
	/*
	public function showStatus($message){
		if (isset($message)) {
			$this->message = $message;
		}
		else{
			$this->message = "<p>" . $message . "</p>";
		}
	}

	// Skickar rättmeddelandet till showStatus.
	public function successfullLogOut(){
		$this->showStatus("Du har nu loggat ut!");
	}
*/
	public function setMessage($message){
		$this->message = $message; 
	}

	// Slutlig presentation av utdata.
	public function showLogin($displayLogoutMessage = false){

		if($displayLogoutMessage){
			$this->message = "Du har nu loggat ut!"; 
		}
		$datetime = $this->getDateTime();
		$ret = RegisterUserView::getRegisterLink(); 
		$ret .= "<h2>Ej inloggad!</h2>";

		$ret .= "
				<fieldset>
				<legend>Logga in här!</legend>";

		$ret .= "<p>$this->message</p>";

		$ret .= "
				<form action='". \config\Config::AppRoot . self::ActionLogin ."' method='post' >";
		
		// Om det inte finns något inmatat användarnamn så visa tom input.
		// Annars visa det tidigare inmatade användarnamnet i input.
		$uservalue = empty($_POST[$this->username]) ? "" : $_POST[$this->username]; 
		$ret .= "Användarnamn: <input type='text' name='$this->username' value='$uservalue'>";
	
		$ret .= "
					Lösenord: <input type='text' name='$this->password'>
					Håll mig inloggad: <input type='checkbox' name='LoginView::checked'>
					<input type='submit' value='Logga in' name='LoginView::login'>
				</form>
				</fieldset>
				";

		$ret .= "<p>$datetime</p>";

		return $ret;
	}

}