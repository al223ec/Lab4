<?php



class CookieService{
	private $cookieTime;

	// Sparar kakan, samtidigt sparas förfallotiden som returneras.
	public function saveCookie($name, $string){
		$cookieTime = strtotime('+1 minutes');
		setcookie($name, $string, $cookieTime);
		return $cookieTime;
	}

	// Laddar kakan.
	public function loadCookie($name){
		if (isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		}
		else{
			return false;
		}
	}

	// Tar bort kakan.
	public function removeCookie($name){
		setcookie ($name, "", time() - 3600);
	}

}