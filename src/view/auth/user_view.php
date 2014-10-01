<?php
namespace view; 

require_once('src/view/view_base.php'); 

class UserView extends ViewBase{

	// Skickar rättmeddelandet till setSessionMessage.
	public function successfullLogIn(){
		$this->model->setSessionReadOnceMessage("Inloggningen lyckades!");
	}

	// Skickar rättmeddelandet till setSessionMessage.
	public function successfullLogInWithCookiesSaved(){
		$this->model->setSessionReadOnceMessage("Inloggningen lyckades och vi kommer ihåg dig nästa gång!");
	}

	// Skickar rättmeddelandet till setSessionMessage.
	public function successfullLogInWithCookiesLoad(){
		$this->model->setSessionReadOnceMessage("Inloggningen lyckades via cookies!");
	}

	// Slutlig presentation av utdata.
	public function showUser(){
		$user = $this->model->getLoggedInUser();
		$ret = "<h2>$user är nu inloggad!</h2>";
		
		$ret .= $this->getMessage();
		$ret .= "
					<form action='". \router::$route['auth']['logout'] . "' method='post' >
					<input type='submit' value='Logga ut' name=''>
					</form>
				";		

		return $ret;
	}


}