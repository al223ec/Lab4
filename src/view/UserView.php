<?php



class UserView{
	
	private $model;
	private $message;


	public function __construct(LoginModel $model){
		$this->model = $model;
	}

	// Kontrollerar om användaren tryckt på Logga ut.
	public function didUserPressLogout(){
		if(isset($_POST["UserView::logout"])){
			return $_POST["UserView::logout"];
		}
		else{
			return false;
		}
	}

	// Datum och tid-funktion. (Kan brytas ut till en hjälpfunktion.)
	public function getDateTime(){
		setlocale(LC_ALL, "sv_SE");
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
	public function successfullLogIn(){
		$this->showStatus("Inloggningen lyckades!");
	}

	// Skickar rättmeddelandet till showStatus.
	public function successfullLogInWithCookiesSaved(){
		$this->showStatus("Inloggningen lyckades och vi kommer ihåg dig nästa gång!");
	}

	// Skickar rättmeddelandet till showStatus.
	public function successfullLogInWithCookiesLoad(){
		$this->showStatus("Inloggningen lyckades via cookies!");
	}

	// Slutlig presentation av utdata.
	public function showUser(){
	$datetime = $this->getDateTime();
	$user = $this->model->getLoggedInUser();

	$ret = "<h1>Laboration 2 - Inloggning - al223bn</h1>";

	$ret .= "<h2>$user är nu inloggad!</h2>";
	
	$ret .= "$this->message";

	$ret .= "
				<form action='?logout' method='post' >
				<input type='submit' value='Logga ut' name='UserView::logout'>
				</form>
			";		

	$ret .= "<p>$datetime</p>";

	return $ret;
}


}