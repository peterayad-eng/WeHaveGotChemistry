<?php

	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login.php");
			exit();
		}
	}

		$fileExtensions=['pdf','doc','docx','xls','xlsx','ppt','pptx'];
		$fileName = $_FILES['answer']['name'];
		$fileSize = $_FILES['answer']['size'];
		$fileTmpName  = $_FILES['answer']['tmp_name'];
		$fileType = $_FILES['answer']['type'];
        $tmp = explode('.', $fileName);
		$fileExtension = strtolower(end($tmp));
        $repeatflag=0;
        

		
		function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
		}
		
		define("SIZE", 52428800);
	  	$user = test_input($_POST['user']);
		$level = test_input($_POST['level']);
        $homework = test_input($_POST['homework']);
        $title = $user.'-'.$homework;
        $new_name = $title.'.'.$fileExtension;
        $new_name_loc = "answers/".$new_name;

		require_once "connect.php";	
		$connec = new con();
		$conn = $connec->connect();
		
        $select_sql = "SELECT * FROM answers";
        $result = mysqli_query($conn, $select_sql);
        $Addedid= $result->num_rows +1;
        for($i=0;$i<$result->num_rows;$i++){
            $row = $result->fetch_assoc();
            if($title == $row['title']){
                $repeatflag = $repeatflag +1;
            }
        }

        if($homework == ""){
            $connec->disconnect($conn);
            header("location: answersAdd.php?adderror=2&id=$Addedid");
        }else{
            if(array_key_exists('answer', $_FILES)){
                if ($_FILES['answer']['error'] === UPLOAD_ERR_OK) {
                    if($fileSize>SIZE){
                        $connec->disconnect($conn);
                        header("location: answersAdd.php?editLerror=2&id=$Addedid");
                    }

                    else if (in_array($fileExtension,$fileExtensions)){
                        $docName = basename($fileName);
                        $sitepath= "answers/".$docName;
                        $uploadpath= "answers/".$docName;
                        $upload= move_uploaded_file($fileTmpName, $uploadpath);
                        if(rename( $uploadpath, $new_name_loc)){
                            if($repeatflag == 0){
                                 $added_sql = "INSERT INTO answers (id, title, url, level) VALUES ('$Addedid', '$title', '$new_name', '$level');";
                                 if ($conn->query($added_sql) === TRUE) {
                                     $connec->disconnect($conn);
                                     header("location: homeworks.php?adderror=0");
                                 } else {
                                     $connec->disconnect($conn);
                                     header("location: answersAdd.php?adderror=1&id=$Addedid");
                                }
                            }else{
                                $connec->disconnect($conn);
                                header("location: homeworks.php?adderror=0");
                            }
                        }else{
                            $connec->disconnect($conn);
                            header("location: answersAdd.php?adderror=1&id=$Addedid");
                        }
                }else{
                    $connec->disconnect($conn);
                    header("location: answersAdd.php?editLerror=3&id=$Addedid");
                 }

             } else {
                 $connec->disconnect($conn);
                 header("location: lessonsAdd.php?adderror=1&id=$Addedid");
             }
        }
    }
?>