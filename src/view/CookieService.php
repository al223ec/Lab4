<?php



class CookieService{
	private $cookieExpiration;  
	// Sparar kakan, samtidigt sparas förfallotiden som returneras.
	public function saveCookie($name, $string = null){ 
		$this->cookieExpiration = time() + 20000; 
		if($string === null){
			$string = $this->generateRandomString(); 
		}

		setcookie($name, $string, $this->cookieExpiration, '/');
		return $string;
	}
	public function getCookieExpiration(){
		return $this->cookieExpiration; 
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
		setcookie ($name, "", time() - 3600, '/');
	}

	/**
	* @param Längden på strängen 
	* @return En random sträng
	*/
	private function generateRandomString($length = 18) {
 	   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}

}