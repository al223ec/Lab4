<?php

class HTMLView{
	public function echoHTML($body){
		if($body === NULL){
			throw new \Exception("HTMLView::echoHTML does not allow body to be null");
		}

		echo "
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset='UTF-8'>
				<link href='css/bootstrap.min.css' rel='stylesheet'>
				<link href='css/style.css' rel='stylesheet'>
				<title> Lab 4 - Anton Ledström </title>
			</head>
			<body>
				<div class='wrapper'>
					<h1>Laboration 4 - Registrera nya användare - al223bn/al223ec</h1>
					$body
				</div>
			</body>
			</html>";
	}
}