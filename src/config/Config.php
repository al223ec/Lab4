<?php

namespace config; 

abstract class Config{
	const UserNameMinLength = 3; 
	const PasswordMinLength = 6;  
	const AppRoot = "/lab4/"; 
	

	public static $ErrorMessages = Array(
		"WrongUserName" => "", 
		"WrongPassword" => "",
		); 


	const ErrorMessageWrongUserName = "";
	const ErrorMessageWrongPassword = ""; 
	const ErrorMessageNoUserName = ""; 
	const ErrorMessageNoPasword = ""; 


}