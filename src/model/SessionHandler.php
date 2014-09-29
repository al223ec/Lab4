<?php

namespace model; 

class SessionHandler{

	private $readOnceMessage = "MySession::ReadOnce";

	public function setSessionArray(array $elemts){
		foreach ($elemts as $key => $value) {
			$_SESSION[$key] = $value;
		}
	}

	public function setSessionReadOnceMessage($message){
		$_SESSION[$this->readOnceMessage] = $message; 
	}
	
	public function getSessionReadOnceMessage(){
		$ret = isset($_SESSION[$this->readOnceMessage]) ? $_SESSION[$this->readOnceMessage] : ""; 
		unset($_SESSION[$this->readOnceMessage]);
		return $ret; 
	}

	public function readFromSession($key){
		isset($_SESSION[$key]) ?  $_SESSION[$key] : "";
	}

	public function setSession($key, $value){
		$_SESSION[$key] = $value; 
	}

	public function getSession($key){
		return isset($_SESSION[$key]) ? $_SESSION[$key] : ""; 
	}

	public function sessionKeyIsSet($key){
		return isset($_SESSION[$key]); 
	}

	/**
	* @return true om det finns en session att ta bort
	*/
	public function unsetSessions(){
		$bool = false; 

		foreach ($_SESSION as $key => $value){
			if ($key !== $this->readOnceMessage){
			    unset($_SESSION[$key]);
			    $bool = true; 
			}
		}
		return $bool; 
	}
}