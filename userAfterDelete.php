<?php

	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login.php");
			exit();
		}
	}

		$deletedid = $_GET['id'];

		require_once "connect.php";	
		$connec = new con();
		$conn = $connec->connect();
		
        

		$delete_sql = "DELETE FROM users WHERE id = '{$deletedid}'";

        if ($conn->query($delete_sql) === TRUE) {
			$select_sql = "SELECT * FROM users";
			$result = mysqli_query($conn, $select_sql);
			for($i=0;$i<$result->num_rows;$i++){
				$row = $result->fetch_assoc();
				$newid=$i;
				$user=$row['user'];
				$update_sql = "UPDATE users SET id = '{$newid}' WHERE user='{$user}'";
				$updateid = mysqli_query($conn, $update_sql);
			}
			$connec->disconnect($conn);
            header("location: users.php?deleteerror=0");
		} else {
		    $connec->disconnect($conn);
			header("location: users.php?deleteerror=1");
		}
?>