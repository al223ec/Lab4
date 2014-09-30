<?php

namespace model; 

class ModelBase {
	
	protected $userRepository; 

	public function __construct(){
		$this->userRepository = new UserRepository();
	}
	
	public function setSessionReadOnceMessage($message){
		sessionHandler::setSessionReadOnceMessage($message); 
	}
	
	public function getSessionReadOnceMessage(){
		return sessionHandler::getSessionReadOnceMessage(); 
	}

	public function setSessionMessage($key, $message){
		sessionHandler::setSession($key, $message); 
	}

	public function readAndRemoveSessionMessage($key){
		return sessionHandler::readAndRemoveSession($key); 
	}
}