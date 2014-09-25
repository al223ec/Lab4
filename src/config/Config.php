<?php

namespace config; 


class Config{
	const UserNameMinLength = 3; 
	const PasswordMinLength = 6;  

	function __construct(){
		throw new Exception("Don't create objects of this type", 1);
	}
}