<?php
namespace view; 
require_once('src/view/ViewBase.php'); 

class UserView extends ViewBase{	

	//Min uppdatering ta bort sträng beroende
	const ActionLogout = "Auth\Logout"; 

	// Skickar rättmeddelandet till setSessionMessage.
	public function successfullLogIn(){
		$this->model->setSessionMessage("Inloggningen lyckades!");
	}

	// Skickar rättmeddelandet till setSessionMessage.
	public function successfullLogInWithCookiesSaved(){
		$this->model->setSessionMessage("Inloggningen lyckades och vi kommer ihåg dig nästa gång!");
	}

	// Skickar rättmeddelandet till setSessionMessage.
	public function successfullLogInWithCookiesLoad(){
		$this->model->setSessionMessage("Inloggningen lyckades via cookies!");
	}

	// Slutlig presentation av utdata.
	public function showUser(){
		$datetime = \helpers::getDateTime();
		$user = $this->model->getLoggedInUser();

		$ret = "<h2>$user är nu inloggad!</h2>";
		
		$ret .= $this->getMessage();
		$ret .= "
					<form action='". \config\Config::AppRoot  . self::ActionLogout . "' method='post' >
					<input type='submit' value='Logga ut' name=''>
					</form>
				";		

		$ret .= "<p>$datetime</p>";

		return $ret;
	}


}