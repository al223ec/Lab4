<?php


class ErrorLogging{
	
	public static function backtrace(){
		echo "<pre>";
		debug_print_backtrace();
		echo "</pre>";
	}  

	public static function pr($message){
		echo "<pre>";
		echo "$message";
		echo "</pre>";
	}
}