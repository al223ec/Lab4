<?php 

//Från http://se1.php.net/get_called_class
abstract class Singleton { 

    protected function __construct() { 
    } 

    final public static function getInstance() { 
        static $aoInstance = array(); 

        $calledClassName = get_called_class(); 

        if (! isset ($aoInstance[$calledClassName])) { 
            $aoInstance[$calledClassName] = new $calledClassName(); 
        } 

        return $aoInstance[$calledClassName]; 
    } 

    final private function __clone() { 
    } 
} 

//Exempel på användning 
class DatabaseConnection extends Singleton { 

    protected $connection; 

    protected function __construct() { 
        // @todo Connect to the database 
    } 

    public function __destruct() { 
        // @todo Drop the connection to the database 
    } 
} 

$oDbConn = new DatabaseConnection();  // Fatal error 
$oDbConn = DatabaseConnection::getInstance();  // Returns single instance 
//argument angående singleton