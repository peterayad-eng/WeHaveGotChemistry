<?php
	session_start();
	unset($_SESSION['user']);
	session_destroy();
	setcookie('user', '', time() - 180);
	header("location: page-login.php");
?>
