<?php

    session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login.php");
			exit();
		}
	}

	require_once "connect.php";	

	  	function test_input($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$user = $_SESSION['user'];
		$password = test_input($_POST['pass']);
        $cpassword = test_input($_POST['cpass']);
        $id = $_POST['id'];
        
		
		$connec = new con();
		$conn = $connec->connect();
		if ($password == $cpassword){
		  $update_sql = "UPDATE users SET pass='{$password}'  WHERE id = '{$id}'";
		  if ($conn->query($update_sql) === TRUE) {
		      $connec->disconnect($conn);
		      header("location: users.php?editPerror=0");
		} else {
		    $connec->disconnect($conn);
			header("location: userEditPass.php?editPerror=1&id=$id");
		      }
        }else{
            $connec->disconnect($conn);
            header("location: userEditPass.php?editPerror=2&id=$id");
        }
