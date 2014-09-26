<?php

namespace config; 

class Config{
	const UserNameMinLength = 3; 
	const PasswordMinLength = 6;  
	const AppRoot = "/lab4/"; 

	function __construct(){
		throw new Exception("Don't create objects of this type", 1);
	}
}