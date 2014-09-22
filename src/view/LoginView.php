<?php

require_once("CookieService.php");

class LoginView{
	
	private $model;
	private $cookieUsername;						// Instans av CookieStorage för att lagra användarnamn.
	private $cookiePassword;						// Instans av CookieStorage för att lagra lösenord.
	private $username = "LoginView::Username";		// Användarnamnets kakas namn.
	private $password = "LoginView::Password";		// Lösenordets kakas namn.
	private $message;								// Privat variabel för att visa fel/rättmeddelanden.

	public function __construct(LoginModel $model){

		// Struktur för MVC.
		$this->model = $model;
		$this->cookieUsername = new CookieService();
		$this->cookiePassword = new CookieService();
	}

	// Kontrollerar om användare tryckt på Logga in.
	public function didUserPressLogin()	{
		if(isset($_POST["LoginView::login"])){
			return $_POST["LoginView::login"];
		}
		else{
			return false;
		}
	}

	// Kontrollerar användare checkat i Håll mig inloggad.
	public function RememberMeIsFilled(){
		if(isset($_POST["LoginView::checked"])){
			return true;
		}
		else{
			return false;
		}
	}

	// Funktion för att hämta sparade kakor.
	public function userIsRemembered(){
		if ($this->cookieUsername->loadCookie($this->username) && $this->cookiePassword->loadCookie($this->password)) {
			return true;
		}
		else{
			return false;
		}
	}

	// Funktion för att spara kakor (och spara ner förfallotid).
	public function saveToCookies($username, $password){
		$this->cookieUsername->saveCookie($this->username, $username);
		$this->model->saveCookieTime($this->cookiePassword->saveCookie($this->password, md5($password)));
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

		if (empty($_POST["$this->username"])) {
			throw new \Exception("Användarnamn saknas!");
		}
		else {
			return $_POST["$this->username"];	
		}
	}

	// Hämtar lösenordet vid rätt input.
	public function getPassword(){

		if (empty($_POST["$this->password"])) {
			throw new \Exception("Lösenord saknas!");	
		}
		else {
			return $_POST["$this->password"];	
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


	// Slutlig presentation av utdata.
	public function showLogin(){

		$datetime = $this->getDateTime();

		$ret = "<h1>Laboration 2 - Inloggning - al223bn</h1>";

		$ret .= "<h2>Ej inloggad!</h2>";

		$ret .= 
				"
				<fieldset>
				<legend>Logga in här!</legend>";

		$ret .= "<p>$this->message";

		$ret .= "
				<form action='?login' method='post' >";

		// Om det inte finns något inmatat användarnamn så visa tom input.
		if(empty($_POST[$this->username])){
			$ret .= "Användarnamn: <input type='text' name='$this->username'>";
		}
		// Annars visa det tidigare inmatade användarnamnet i input.
		else{
			$uservalue = $_POST[$this->username];
			$ret .= "Användarnamn: <input type='text' name='$this->username' value='$uservalue'>";
		}

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