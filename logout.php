<?php
	session_start();
    $user = $_SESSION['user'];
	unset($_SESSION['user']);
	session_destroy();
	setcookie('user', '', time() - 180);
    $log = "2202\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has logged out successfully \n";
    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
	header("location: page-login");
    exit;
?>
