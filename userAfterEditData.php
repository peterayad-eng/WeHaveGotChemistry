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
		
        $id = $_POST['id'];
	  	$user = test_input($_POST['user']);
		$level = test_input($_POST['level']);
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
                    if($id == $row['id']){
                        continue;
                    }else{
                        $repeatflag=$repeatflag+1;
                    }
                }
        }
        
        
        if($repeatflag == 0){
            $update_sql = "UPDATE users SET user='{$user}', level='{$level}'  WHERE id = '{$id}'";
            if ($conn->query($update_sql) === TRUE) {
                $connec->disconnect($conn);
                header("location: users.php?editDerror=0");
            } else {
                $connec->disconnect($conn);
                header("location: usersEditData.php?editDerror=1&id=$id");
            }
        }else{
            $connec->disconnect($conn);
            header("location: usersEditData.php?editDerror=2&id=$id");
        }
?>