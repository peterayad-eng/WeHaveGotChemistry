<?php
	session_start();
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login");
			exit();
		}
	}
 
	require_once "connect.php";	
	$connec = new con();

    require_once "session.php";
	require_once "header.php";
	require_once "documentEditData.php";
	require_once "footer.php";
	
	$connec->close();
?>