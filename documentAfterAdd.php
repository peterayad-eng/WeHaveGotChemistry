<?php

	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login.php");
			exit();
		}
	}

		$fileExtensions=['pdf','doc','docx','xls','xlsx','ppt','pptx'];
		$fileName = $_FILES['url']['name'];
		$fileSize = $_FILES['url']['size'];
		$fileTmpName  = $_FILES['url']['tmp_name'];
		$fileType = $_FILES['url']['type'];
        $tmp = explode('.', $fileName);
		$fileExtension = strtolower(end($tmp));


		
		function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
		}
		
		define("SIZE", 52428800);
	  	$title = test_input($_POST['title']);
		$level = test_input($_POST['level']);
        $par = test_input($_POST['par']);
        $repeatflag=0;

		require_once "connect.php";	
		$connec = new con();
		$conn = $connec->connect();
		
        $select_sql = "SELECT * FROM documents";
        $result = mysqli_query($conn, $select_sql);
        $Addedid= $result->num_rows +1;
        for($i=0;$i<$Addedid;$i++){
                $row = $result->fetch_assoc();
                if($title == $row['title']){
                    $repeatflag=$repeatflag+1;
                }
        }
    
        if($repeatflag == 0){
            if(array_key_exists('url', $_FILES)){
                if ($_FILES['url']['error'] === UPLOAD_ERR_OK) {
                    if($fileSize>SIZE){
                        $connec->disconnect($conn);
                        header("location: documentsAdd.php?editLerror=2&id=$Addedid");
                    }

                    else if (in_array($fileExtension,$fileExtensions)){
                        $docName = basename($fileName);
                        $select_sql = "SELECT * FROM documents";
                        $result = mysqli_query($conn, $select_sql);
                        for($i=0;$i<$result->num_rows;$i++){
                            $row = $result->fetch_assoc();
                            $url = $row['url'];
                            if ($docName == $url ){
                                $docName = "a".$docName;
                            }
                        }
                        $sitepath= "documents/".$docName;
                        $uploadpath= "documents/".$docName;
                        $upload= move_uploaded_file($fileTmpName, $uploadpath);
                        $added_sql = "INSERT INTO documents (id, title, par, url, level) VALUES ('$Addedid', '$title', '$par', '$docName', '$level');";
                        if ($conn->query($added_sql) === TRUE) {
                            $connec->disconnect($conn);
                            header("location: documents.php?adderror=0");
                        } else {
                            $connec->disconnect($conn);
                            header("location: documentsAdd.php?adderror=1&id=$Addedid");
                        }
                    }else{
                        $connec->disconnect($conn);
                        header("location: documentsAdd.php?editLerror=3&id=$Addedid");
                    }

                } else {
                    $connec->disconnect($conn);
                    header("location: documentsAdd.php?adderror=1&id=$Addedid");
                }
            }
        }else{
            $connec->disconnect($conn);
            header("location: documentsAdd.php?adderror=2&id=$Addedid");
        }
?>