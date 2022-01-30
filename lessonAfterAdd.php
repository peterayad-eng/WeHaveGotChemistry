<?php

	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login.php");
			exit();
		}
	}

		$fileExtensions=['jpeg','jpg','png'];
		$fileName = $_FILES['image']['name'];
		$fileSize = $_FILES['image']['size'];
		$fileTmpName  = $_FILES['image']['tmp_name'];
		$fileType = $_FILES['image']['type'];
        $tmp = explode('.', $fileName);
		$fileExtension = strtolower(end($tmp));


		
		function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
		}
		
		define("SIZE", 5242880);
	  	$caption = test_input($_POST['caption']);
		$level = test_input($_POST['level']);
        $urlRaw = test_input($_POST['url']);
        $counter = 0;
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
        $Addedid= $result->num_rows +1;
        for($i=0;$i<$Addedid;$i++){
                $row = $result->fetch_assoc();
                if($caption == $row['caption']){
                    $repeatflag=$repeatflag+1;
                }
        }
    
        if($repeatflag == 0){
            if(array_key_exists('image', $_FILES)){
                if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    if($fileSize>SIZE){
                        $connec->disconnect($conn);
                        header("location: lessonsAdd.php?editLerror=2&id=$Addedid");
                    }

                    else if (in_array($fileExtension,$fileExtensions)){
                        $imageName = basename($fileName);
                        $select_sql = "SELECT * FROM videos";
                        $result = mysqli_query($conn, $select_sql);
                        for($i=0;$i<$result->num_rows;$i++){
                            $row = $result->fetch_assoc();
                            $image = $row['image'];
                            if ($imageName == $image ){
                                $imageName = "a".$imageName;
                            }
                        }
                        $sitepath= "Images/".$imageName;
                        $uploadpath= "Images/".$imageName;
                        $upload= move_uploaded_file($fileTmpName, $uploadpath);
                        $added_sql = "INSERT INTO videos (id, url, level, image, caption, counter) VALUES ('$Addedid', '$url', '$level', '$imageName', '$caption', '$counter');";
                        if ($conn->query($added_sql) === TRUE) {
                            $connec->disconnect($conn);
                            header("location: index.php?adderror=0");
                        } else {
                            $connec->disconnect($conn);
                            header("location: lessonsAdd.php?adderror=1&id=$Addedid");
                        }
                    }else{
                        $connec->disconnect($conn);
                        header("location: lessonsAdd.php?editLerror=3&id=$Addedid");
                    }

                } else {
                    $connec->disconnect($conn);
                    header("location: lessonsAdd.php?adderror=1&id=$Addedid");
                }
            }
        }else{
            $connec->disconnect($conn);
            header("location: lessonsAdd.php?adderror=2&id=$Addedid");
        }
?>