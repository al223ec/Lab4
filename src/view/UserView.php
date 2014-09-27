<?php

class UserView{
	
	private $model;
	private $message;

	//Min uppdatering ta bort sträng beroende
	const ActionLogout = "Auth\Logout"; 

	public function __construct(\model\AuthModel $model){
		$this->model = $model;
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

		$ret = "<h2>$user är nu inloggad!</h2>";
		
		$ret .= "$this->message";

		$ret .= "
					<form action='". \config\Config::AppRoot  . self::ActionLogout . "' method='post' >
					<input type='submit' value='Logga ut' name=''>
					</form>
				";		

		$ret .= "<p>$datetime</p>";

		return $ret;
	}


}