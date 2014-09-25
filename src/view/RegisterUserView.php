<?php

require_once('src/model/User.php'); 
require_once('src/config/Config.php');  

class RegisterUserView{
	
	private $loginmodel; 

	const ActionRegister = "RegisterUserView::Register"; 
	const ActionSaveNewUser = "RegisterUserView::SaveNewUser"; 
	
	private $userName = "RegisterUserView::UserName";	
	private $password = "RegisterUserView::Password";	

	private $repeatedPassword = "RegisterUserView::RepeatedPassword";	

	private $errorMessages; 
	const PasswordErrorKey = "PasswordError"; 
	const UserNameErrorKey = "UserNameError"; 

	public function __construct(LoginModel $loginmodel){
		$this->loginmodel = $loginmodel; 
		$this->errorMessages = array(); 
	}

	public function didUserPressRegister(){
		return isset($_GET[self::ActionRegister]);
	}

	public function didUserPressSaveNewUser(){
		return isset($_GET[self::ActionSaveNewUser]);
	}

	private function getUserName(){
		$ret = $this->getCleanInput($this->userName);

		if($ret !== $this->getInput($this->userName)){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet innehåller ogiltiga tecken!";
			$ret = ""; 
		}else if($ret === ""){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet saknas";
		} else if($this->loginmodel->ceckIfUserNameExists($ret)){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet finns redan";
		}
		//Kontrollera om användarnamnet är taget!!!
		return $ret; 
	}

	private function getPassword(){

		$ret = $this->getCleanInput($this->password);
		if($ret === ""){
			$this->errorMessages[self::PasswordErrorKey] = "Lösenordet saknas";
		} 
		if ($ret !== $this->getCleanInput($this->repeatedPassword)) {
			$this->errorMessages[self::PasswordErrorKey] = "Lösenorden stämmer inte överens";
			$ret = "";	
		}

		return $ret; 
		/*
		if($ret !== $this->getInput($this->password) || $this->getCleanInput($this->repeatedPassword) !== $this->getInput($this->repeatedPassword)){
			$this->errorMessages[self::PasswordErrorKey] = "Lösenordet innehåller ogiltiga tecken!";
			$ret = ""; 
		}else if($ret === ""){
			$this->errorMessages[self::PasswordErrorKey] = "Lösenordet saknas";
		} 
		if ($ret !== $this->getCleanInput($this->repeatedPassword)) {
			if(isset($this->errorMessages[self::PasswordErrorKey])){ 
				$this->errorMessages[self::PasswordErrorKey] .= "Lösenorden stämmer inte överens"; 
			} else {
				$this->errorMessages[self::PasswordErrorKey] = "Lösenorden stämmer inte överens";
			}
			$ret = ""; 
		}
		return $ret; */
	}


	public function getRegisterLink(){
		return "<a href='?". self::ActionRegister ."'> Registrera ny användare </a>"; 
	}

	public function getRegisterForm($prompt = ""){
		return" 
				<h2>Ej inloggad, registrera ny användare!</h2>
				<fieldset>
				<legend>Registrera ny användare - skriv in användarnamn och lösenord</legend>
				<p> ". $prompt . "
				<form action='?" . self::ActionSaveNewUser . "' method='post' >
				<fieldset>
					<label for='RegisterUserNameID' >Namn  :</label>
					<input type='text' name='" . $this->userName . "' id='RegisterUserNameID'>"
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

	public function getNewUser(){
		$userName = $this->getUserName(); 
		$password = $this->getPassword(); 

		if($userName !== "" && $password !== ""){
			$newUser = new \model\User(); 
			
			$newUser->setUserName($userName); 
			$newUser->setPassword($password);  
		} 
		return null; 
	}

	private function getInput($inputName){
		return isset($_POST[$inputName]) ? $_POST[$inputName] : "";
	}
	private function getCleanInput($inputName) {
		return isset($_POST[$inputName]) ? $this->sanitize($_POST[$inputName]) : "";
	}
	private function sanitize($input) {
        $temp = trim($input);
        return filter_var($temp, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    }
	
	private function getErrorMessages($key){
		if (isset($this->errorMessages[$key])) {
			return "<span class='errormessage'> " . $this->errorMessages[$key] . " </span>"; 
		}
	}

	/** Fuktion för att lägga till errormessages utanför klassen
	*
	*/
	public function addErrorMessage($key, $errorMessage){
		if($key === self::PasswordError || $key === self::UserNameError){
			$this->errorMessages[$key] = $errorMessage; 
		} else { 
			throw new \Exception("LoginView::addErrorMessage fel nyckel skickad till funktionen!!");
		}
	}
}