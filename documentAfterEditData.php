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
	  	$title = test_input($_POST['title']);
		$level = test_input($_POST['level']);
        $par = test_input($_POST['par']);
        $repeatflag=0;

		require_once "connect.php";	
		$connec = new con();
		$conn = $connec->connect();
		
        $select_sql = "SELECT * FROM documents";
        $result = mysqli_query($conn, $select_sql);
        $Addedid= $result->num_rows;
        for($i=0;$i<$Addedid;$i++){
                $row = $result->fetch_assoc();
                if($title == $row['title']){
                    if($id == $row['id']){
                        continue;
                    }else{
                        $repeatflag=$repeatflag+1;
                    }
                }
        }

        if($repeatflag == 0){
            $update_sql = "UPDATE documents SET title='{$title}', level='{$level}', par='{$par}'  WHERE id = '{$id}'";
            if ($conn->query($update_sql) === TRUE) {
                $connec->disconnect($conn);
                header("location: documents.php?editDerror=0");
            } else {
                $connec->disconnect($conn);
                header("location: documentsEditData.php?editDerror=1&id=$id");
            } 
        }else{
            $connec->disconnect($conn);
            header("location: documentsEditData.php?editDerror=2&id=$id");
        }
?>