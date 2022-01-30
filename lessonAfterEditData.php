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
	  	$caption = test_input($_POST['caption']);
		$level = test_input($_POST['level']);
        $urlRaw = test_input($_POST['url']);
        $repeatflag=0;

        // adjust url to google drive form
        $urlpre = substr($urlRaw, strrpos($urlRaw, '/' )+1);
        $url = str_replace($urlpre, "", $urlRaw);
        $url = $url.'preview';

		require_once "connect.php";	
		$connec = new con();
		$conn = $connec->connect();
		
        $select_sql = "SELECT * FROM videos";
        $result = mysqli_query($conn, $select_sql);
        $Addedid= $result->num_rows;
        for($i=0;$i<$Addedid;$i++){
                $row = $result->fetch_assoc();
                if($caption == $row['caption']){
                    if($id == $row['id']){
                        continue;
                    }else{
                        $repeatflag=$repeatflag+1;
                    }
                }
        }

        if($repeatflag == 0){
            $update_sql = "UPDATE videos SET url='{$url}', level='{$level}', caption='{$caption}'  WHERE id = '{$id}'";
            if ($conn->query($update_sql) === TRUE) {
                $connec->disconnect($conn);
                header("location: index.php?editDerror=0");
            } else {
                $connec->disconnect($conn);
                header("location: lessonsEditData.php?editDerror=1&id=$id");
            } 
        }else{
            $connec->disconnect($conn);
            header("location: lessonsEditData.php?editDerror=2&id=$id");
        }
?>