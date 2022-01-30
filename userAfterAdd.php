<?php

	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login.php");
			exit();
		}
	}

		function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
		}
		
	  	$user = test_input($_POST['user']);
		$pass = test_input($_POST['pass']);
        $cpass = test_input($_POST['cpass']);
        $level = test_input($_POST['level']);
        $type = "student";
        $repeatflag=0;

		require_once "connect.php";	
		$connec = new con();
		$conn = $connec->connect();
		
        $select_sql = "SELECT * FROM users";
        $result = mysqli_query($conn, $select_sql);
        $Addedid= $result->num_rows;
        for($i=0;$i<$Addedid;$i++){
                $row = $result->fetch_assoc();
                if($user == $row['user']){
                    $repeatflag=$repeatflag+1;
                }
        }
        
        if($repeatflag == 0){
            if ($pass == $cpass){
                $added_sql = "INSERT INTO users (id, user, pass, type, level) VALUES ('$Addedid', '$user', '$pass', '$type', '$level');";
                if ($conn->query($added_sql) === TRUE) {
                    $connec->disconnect($conn);
                    header("location: users.php?adderror=0");
                } else {
                    $connec->disconnect($conn);
                    header("location: usersAdd.php?adderror=1&id=$Addedid");
                }
            }else{
                $connec->disconnect($conn);
                header("location: usersAdd.php?adderror=2&id=$Addedid");
            }
        }else{
            $connec->disconnect($conn);
            header("location: usersAdd.php?adderror=3&id=$Addedid");
        }
?>