<?php 
	// functions.php
	require("/home/paulvase/config.php");
	
	// et saab kasutada $_SESSION muutujaid
	// kõigis failides mis on selle failiga seotud
	session_start(); 
	
	/* ÜHENDUS */
	$database = "if16_paulvase_3";
	$mysqli = new mysqli($serverHost, $serverUsername,  $serverPassword, $database);

	
?>