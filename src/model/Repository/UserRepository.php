<?php

namespace model; 

require_once("src/model/Repository/Repository.php"); 
require_once("src/model/User.php"); 

class UserRepository Extends Repository{
	
	private $userNameParam = ":userName"; 
	private $passwordHashParam = ":passwordHash"; 

	public function addUser(User $newUser){
		try{
			$userName = $newUser->getUserName(); 
			$passwordHash = $newUser->getPasswordHash(); 
			
			$sql = "INSERT INTO " . self::$TBL_NAME . "(userName, passwordHash) VALUES(" . $this->userNameParam ."," . $this->passwordHashParam .")";
			$sth = $this->pdo->prepare($sql); 	

			$sth->bindParam($this->userNameParam, $userName);
			$sth->bindParam($this->passwordHashParam, $passwordHash);
			
			if(!$sth->execute()){
				throw new \Exception("SQL Execute Error"); 
			} 
			return true; 
		}
		catch(\Exception $e){
			return false; 
		}
	}

	public function getUser($userName){
		try{
			$ret = null; 
			$sql = "SELECT * FROM " . self::$TBL_NAME . " WHERE userName = " . $this->userNameParam;  
			
			$sth = $this->pdo->prepare($sql); 
			
			if(!$sth){
				throw new \Exception("SQL Error"); 
			} 

			$sth->bindParam($this->userNameParam, $userName);
			if(!$sth->execute()){
				throw new \Exception("SQL Execute Error"); 
			} 
			if($response = $sth->fetch(\PDO::FETCH_OBJ)){
				$ret = new User($response->userID, $response->userName, $response->passwordHash); 				
			} 
			return $ret; 
		}
		catch(\Exception $ex){
			return null; 
		}
	}


}