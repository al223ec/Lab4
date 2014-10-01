<?php

namespace model; 

class SessionHandler{

	//Statisk klass för att hantera sessions, denna klass ska vara singelton över appens livscykel
	private static $readOnceMessage = "MySession::ReadOnce";

	/**
	* Vill inte skapa några instanser av detta objekt 
	* detta för att tydliggöra att det endast finns "en" session att tillgå i applikationen, om jag nu fattat den biten korrekt. 
	* 
	*/
	private function __construct(){ }


	public static function setSessionArray(array $elemts){
		foreach ($elemts as $key => $value) {
			$_SESSION[$key] = $value;
		}
	}

	public static function setSessionReadOnceMessage($message){
		$_SESSION[self::$readOnceMessage] = $message; 
	}
	
	public static function getSessionReadOnceMessage(){
		$ret = isset($_SESSION[self::$readOnceMessage]) ? $_SESSION[self::$readOnceMessage] : ""; 
		unset($_SESSION[self::$readOnceMessage]);
		return $ret; 
	}

	public static function readAndRemoveSession($key){
		$ret = isset($_SESSION[$key]) ? $_SESSION[$key] : ""; 
		unset($_SESSION[$key]);
		return $ret; 
	}

	public static function readFromSession($key){
		isset($_SESSION[$key]) ?  $_SESSION[$key] : "";
	}

	public static function setSession($key, $value){
		$_SESSION[$key] = $value; 
	}

	public static function getSession($key){
		return isset($_SESSION[$key]) ? $_SESSION[$key] : ""; 
	}

	public static function sessionKeyIsSet($key){
		return isset($_SESSION[$key]); 
	}

	/**
	* @return true om det finns en session att ta bort
	*/
	public static function unsetSessions(){
		$bool = false; 

		foreach ($_SESSION as $key => $value){
			if ($key !== self::$readOnceMessage){
			    unset($_SESSION[$key]);
			    $bool = true; 
			}
		}
		return $bool; 
	}
}