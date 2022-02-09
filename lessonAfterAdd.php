<?php
	session_start();
	if(!isset($_SESSION['user']) || $_SESSION['user'] == ""){
		if(!isset($_COOKIE['user'])){
			header("location: page-login");
			exit();
		}
	}

    require_once "session.php";
    require_once "connect.php";	
    $connec = new con();

    $user=$_SESSION['user'];
    $select_sql = $connec->query('SELECT id, type, level FROM users WHERE user = ?', $user)->fetchArray();
    $type = $select_sql['type'];

    if($type == "teacher"){
        $fileExtensions=['jpeg','jpg','png','webp'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileTmpName  = $_FILES['image']['tmp_name'];
        $fileType = $_FILES['image']['type'];
        $tmp = explode('.', $fileName);
        $fileExtension = strtolower(end($tmp));
        define("SIZE", 5242880);
	
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $caption = test_input($_POST['caption']);
        $level = test_input($_POST['level']);
        $urlRaw = test_input($_POST['url']);
        $counter = 0;
        $repeatflag=0;
        
        $rows = $connec->query('SELECT * FROM videos')->numRows();
        if($rows==0){
            $Addedid = 0;
        }else{
            $last_row = $connec->query('SELECT * FROM videos ORDER BY id DESC LIMIT 1')->fetchArray();
            $Addedid = $last_row['id'] + 1;
        }

        // adjust url to google drive form
        $urlpre = substr($urlRaw, strrpos($urlRaw, '/' )+1);
        $url = str_replace($urlpre, "", $urlRaw);
        $url = $url.'preview';
		
        $select_sql = $connec->query('SELECT * FROM videos')->fetchAll();
        foreach($select_sql as $video){
                if($caption == $video['caption']){
                    $repeatflag=$repeatflag+1;
                }
        }
    
        if($repeatflag == 0){
            if(array_key_exists('image', $_FILES)){
                if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    if($fileSize>SIZE){
                        $connec->close();
                        $log = "3321\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add a lesson with an oversized Image: '".$fileName."', and Image size: '".$fileSize."' \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: lessonsAdd?editLerror=2");
                        exit;
                    }

                    if (in_array($fileExtension,$fileExtensions)){
                        $imageName = basename($fileName);
                        
                        do{
                            $editflag=0;
                            foreach($select_sql as $video){
                                if ($imageName == $video['image']){
                                    $imageName = "a".$imageName;
                                    $editflag=1;
                                }
                            }
                        }while($editflag==1);
                        
                        $sitepath= "Images/".$imageName;
                        $uploadpath= "Images/".$imageName;
                        $upload= move_uploaded_file($fileTmpName, $uploadpath);
                        $insert_sql = $connec->query('INSERT INTO videos (id, url, level, image, caption, counter) VALUES (?,?,?,?,?,?)', $Addedid, $url, $level, $imageName, $caption, $counter);
                        $connec->close();
                        $log = "2301\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has added a lesson: '".$caption."' with an id: '".$Addedid."' successfully \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: index?adderror=0");
                        exit;
                    }else{
                        $connec->close();
                        $log = "3322\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add a lesson with Image: '".$fileName."' that has an unallowed extension, and Image size: '".$fileSize."' \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: lessonsAdd?editLerror=3");
                        exit;
                    }

                } else {
                    $connec->close();
                    $log = "3323\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add a lesson but Image: '".$fileName."' with size: '".$fileSize."' could not be uploaded \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: lessonsAdd?adderror=1");
                    exit;
                }
            }
        }else{
            $connec->close();
            $log = "3301\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add a lesson: '".$caption."' that is already exist \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: lessonsAdd?adderror=2");
            exit;
        }
    }else{
        $connec->close();
        $log = "4251\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to add a lesson \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>