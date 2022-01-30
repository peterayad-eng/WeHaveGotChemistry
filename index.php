<?php
	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login.php");
			exit();
		}
	}
 
	require_once "connect.php";	
	$connec = new con();
	$conn = $connec->connect();

    require_once "session.php";
	require_once "header.php";
	require_once "main.php";
	require_once "footer.php";
	
	$connec->disconnect($conn);
?>