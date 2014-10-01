<?php 

namespace model; 

abstract class Repository{
			/* local /*/ 
	protected static $DB_PASSWORD = ""; 
	protected static $DB_USERNAME = "root"; 
	protected static $TBL_NAME = "users"; 
	protected static $CONNECTIONSTRING = "mysql:host=127.0.0.1;dbname=lab4db";

	protected $pdo; 
	public function __construct(){
		$this->pdo = self::connect(); 
	}

	protected static function connect(){
		return new \PDO(self::$CONNECTIONSTRING, self::$DB_USERNAME, self::$DB_PASSWORD);
	} 
	/**
	*stulen metod från Emil, inte testat eller använd än, 
	*/
	public function query($sql, $params = NULL) {

		$db = $this -> connection();

		$query = $db -> prepare($sql);
		$result;
		if ($params != NULL) {
			if (!is_array($params)) {
				$params = array($params);
			}

			$result = $query -> execute($params);
		} else {
			$result = $query -> execute();
		}

		if ($result) {
			return $result -> fetchAll();
		}

		return NULL;

	}
}