<?php

require_once('src/model/User.php'); 

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
		if($ret === ""){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet saknas";
		} 
		//Kontrollera om användarnamnet är taget!!!
		return $ret; 
	}

	private function getPassword(){
		$ret = $this->getCleanInput($this->password);
		if($ret === ""){
			$this->errorMessages[self::PasswordErrorKey] = "Lösenordet saknas";
		}else if ($ret !== $this->getCleanInput($this->repeatedPassword)) {
			$this->errorMessages[self::PasswordErrorKey] = "Lösenorden stämmer inte överens";
			$ret = ""; 
		}
		return $ret; 
	}


	public function getRegisterLink(){
		return "<a href='?". self::ActionRegister ."'> Registrera ny användare </a>"; 
	}

	public function getRegisterForm(){
		return" 
				<h2>Ej inloggad, registrera ny användare!</h2>
				<fieldset>
				<legend>Registrera ny användare - skriv in användarnamn och lösenord</legend>
				<form action='?" . self::ActionSaveNewUser . "' method='post' >
				<label for='RegisterUserNameID' >Namn  :</label>
				<input type='text' name='" . $this->userName . "' is='RegisterUserNameID'>
				<label for='PasswordID' >Lösenord  :</label>
				<input type='text' name='" . $this->password . "' id='PasswordID'>

				<label for='RepeatedPasswordID' >Repetera lösenord  :</label>
				<input type='text' name='". $this->repeatedPassword . "' id='RepeatedPasswordID'>
				<input type='submit' value='Registrera' name='LoginView::login'>
				</form>
				</fieldset>
				";
	}

	public function getNewUser(){
		$userName = $this->getUserName(); 
		$password = $this->getPassword(); 
		if($userName !== "" && $password !== ""){
			return new \model\User(0, $this->getUserName(), $this->getPassword()); 
		} 
		return null; 
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
			return "<span> " . $this->errorMessages[$key] . " </span>"; 
		}
	}
}