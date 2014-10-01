<?php

namespace model; 

require_once("src/model/repository/repository.php"); 
require_once("src/model/user.php"); 

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
			throw $e; 
		}
	}

	public function getUserWithUserName($userName){
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
				$ret = new User($response->userID); 

				$ret->setUserName($response->userName); 
				$ret->setPasswordHash($response->passwordHash); 				
				$ret->setCookieValue($response->cookieValue); 
				$ret->setCookieTime($response->cookieTime);
			} 
			return $ret; 
		}
		catch(\Exception $ex){
			return null; 
		}
	}

	public function saveCookieValue($userID, $cookieValue, $cookieTime){
		try{
			$sql = "UPDATE " . self::$TBL_NAME . " SET cookieValue = ?, cookieTime = ? WHERE UserID = ?";
			$sth = $this->pdo->prepare($sql);

	        if ($sth === FALSE) {
	            throw new \Exception("prepare of $sql failed " . $this->pdo->errorInfo());
	        }	
			
			$sth->bindParam(1, $cookieValue, \PDO::PARAM_STR); 
			$sth->bindParam(2, $cookieTime, \PDO::PARAM_INT); 
			$sth->bindParam(3, $userID, \PDO::PARAM_INT); 

	        if ($sth->execute() === FALSE) {
	            throw new \Exception("execute of $sql failed " . $this->pdo->errorInfo());
	        }
	        return true; 
		}
		catch(\Exception $ex){
			return false; 
		}
	}

	public function resetCookieValues($userID){
		return $this->saveCookieValue($userID, null, 0); 
	}
}