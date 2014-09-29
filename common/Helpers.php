<?php

class Helpers{
	
	public function getUserAgent(){
		return $_SERVER['HTTP_USER_AGENT'];
	}

	// Datum och tid-funktion. (Kan brytas ut till en hjälpfunktion.)
	public static function getDateTime(){
		setlocale(LC_ALL, "sv_SE");
		$weekday = ucfirst(utf8_encode(strftime("%A,")));
		$date = strftime("den %d");
		$month = strftime("%B");
		$year = strftime("år %Y.");
		$time = strftime("Klockan är [%H:%M:%S].");
		return "$weekday $date $month  $year  $time";
	}
}