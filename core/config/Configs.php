<?php

class Configs{
	
	const DefaultController = "Auth";
	const DefaultAction = "main"; 
	const AllowedUrlChars = "/[^A-z0-9\/\^]/"; 


	public function __construct(){
		throw new \Exception("Don't create objects of this type only access in a static way");
	}
}