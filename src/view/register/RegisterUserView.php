<?php

namespace view; 

require_once('src/model/User.php'); 
require_once('src/config/Config.php'); 
require_once('src/view/ViewBase.php'); 

class RegisterUserView extends ViewBase{
	
	const ActionRegister = 		"RegisterUser/"; 
	const ActionSaveNewUser = 	"RegisterUser/SaveNewUser"; 
	
	private $userName = 		"RegisterUserView::UserName";	
	private $password = 		"RegisterUserView::Password";	
	private $repeatedPassword = "RegisterUserView::RepeatedPassword";	

	private $errorMessages; 
	const PasswordErrorKey = 	"PasswordError"; 
	const UserNameErrorKey = 	"UserNameError"; 

	public function __construct(\model\ RegisterUserModel $model){
		parent::__construct($model);
		$this->errorMessages = array(); 
	}

	public function setSuccessMessage($userName){
		$this->model->setSessionReadOnceMessage("Registrering av ny användare lyckades " . $userName);
		$this->setSession($userName); 
	}

	public function setFailMessage(){
		$this->model->setSessionReadOnceMessage("Registrering av ny användare misslyckades fatalt vg försök igen!");
	}


	public function getUserName(){
		$ret = $this->getCleanInput($this->userName);
		if($ret !== $this->getInput($this->userName)){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet innehåller ogiltiga tecken!";
			$ret = ""; 
		}else if($ret === ""){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet saknas";
		}else if($this->model->ceckIfUserNameExists($ret)){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet finns redan";
			$ret = ""; 
		}else if(strlen($ret) < \config\Config::UserNameMinLength){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet är för kort";
			$ret = "";
		}

		return $ret; 
	}

	public function getPassword(){
		$ret = $this->getCleanInput($this->password);
		if($ret === ""){
			$this->errorMessages[self::PasswordErrorKey] = "Lösenordet saknas";
		} 
		if ($ret !== $this->getCleanInput($this->repeatedPassword)) {
			$this->errorMessages[self::PasswordErrorKey] = "Lösenorden stämmer inte överens";
			$ret = "";	
		}else if(strlen($ret) < \config\Config::PasswordMinLength){
			$this->errorMessages[self::PasswordErrorKey] = "Lösenordet är för kort!";
			$ret = "";
		}
		return $ret; 
	}


	public static function getRegisterLink(){
		return "<a href='". \config\Config::AppRoot . self::ActionRegister ."'> Registrera ny användare </a>"; 
	}

	public function getRegisterForm(){
		return "				
				<h2>Ej inloggad, registrera ny användare!</h2>
				<fieldset>
				<legend>Registrera ny användare - skriv in användarnamn och lösenord</legend>
				". $this->getMessage() . "
				<form action='". \config\Config::AppRoot . self::ActionSaveNewUser . "' method='post' >
				<fieldset>
					<label for='RegisterUserNameID' >Namn  :</label>
					<input type='text' name='" . $this->userName . "' id='RegisterUserNameID' value=". $this->getInput($this->userName) .">"
					. $this->getErrorMessages(self::UserNameErrorKey) . 
					"
				</fieldset>
				<fieldset>
					<label for='PasswordID' >Lösenord  :</label>
					<input type='text' name='" . $this->password . "' id='PasswordID'>
					" . $this->getErrorMessages(self::PasswordErrorKey) . "
				</fieldset>
				<fieldset>
					<label for='RepeatedPasswordID' >Repetera lösenord  :</label>
					<input type='text' name='". $this->repeatedPassword . "' id='RepeatedPasswordID'>
					" . $this->getErrorMessages(self::PasswordErrorKey) . "
				</fieldset>
				<input type='submit' value='Registrera' name='LoginView::login'>
				</form>
				</fieldset>
				";
	}

	private function getErrorMessages($key){
		if (isset($this->errorMessages[$key])) {
			return "<span class='errormessage'> " . $this->errorMessages[$key] . " </span>"; 
		}
	}
}