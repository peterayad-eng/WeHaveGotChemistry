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
        $id = $_POST['id'];
        $flag = $_POST['flag'];
        
		
		$connec = new con();
		$conn = $connec->connect();
		
		$select_sql = "SELECT pass FROM users WHERE user = '{$user}'";
		$result = mysqli_query($conn, $select_sql);
        $row = $result->fetch_assoc();
        $connec->disconnect($conn);
        if($row['pass'] == $password){
            if($flag == 0){
                header("location: userEditPass.php?id=$id");
            }elseif ($flag == 1){
                header("location: userAfterDelete.php?id=$id");
            }		
        }
        else{
            header("location: confirmPass.php?error=1&id=$id&flag=$flag");
        }	
