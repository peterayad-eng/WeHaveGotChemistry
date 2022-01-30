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

        $file_sql = "SELECT * FROM homework WHERE id = '{$deletedid}'";
        $fileresult = mysqli_query($conn, $file_sql);
        $filerow = $fileresult->fetch_assoc();
        $url=$filerow['url'];
        $url="homework/".$url;

		$delete_sql = "DELETE FROM homework WHERE id = '{$deletedid}'";

        if ($conn->query($delete_sql) === TRUE) {
            unlink($url);
			$select_sql = "SELECT * FROM homework";
			$result = mysqli_query($conn, $select_sql);
			for($i=0;$i<$result->num_rows;$i++){
				$row = $result->fetch_assoc();
				$newid=$i+1;
				$title=$row['title'];
				$update_sql = "UPDATE homework SET id = '{$newid}' WHERE title='{$title}'";
				$updateid = mysqli_query($conn, $update_sql);
			}
			$connec->disconnect($conn);
            header("location: homeworks.php?deleteerror=0");
		} else {
		    $connec->disconnect($conn);
			header("location: homeworks.php?deleteerror=1");
		}
?>