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
        $id = $_POST['id'];
        define("SIZE", 5242880);
		
		if(array_key_exists('image', $_FILES)){
			if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
				if($fileSize>SIZE){
				    $log = "3361\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to edit the image of the lesson that has an id: '".$id."' with an oversized Image: '".$fileName."', and Image size: '".$fileSize."' \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: lessonsEditImg?editIerror=2&id=$id");
                    exit;
				}
				
				if (in_array($fileExtension,$fileExtensions)){
                    $imageName = basename($fileName);
                    $select_sql = $connec->query('SELECT * FROM videos')->fetchAll();
                    
                    do{
                        $editflag=0;
                        foreach($select_sql as $video){
                            if ($imageName == $video['image'] ){
                                $imageName = "a".$imageName;
                                $editflag=1;
                            }
                        }
                    }while($editflag==1);
                    
                    $select_sqli = $connec->query('SELECT * FROM videos WHERE id = ?', $id)->fetchArray();
                    $oldImage = $select_sqli['image'];
					$sitepath= "Images/".$imageName;
					$uploadpath= "Images/".$imageName;
					$upload= move_uploaded_file($fileTmpName, $uploadpath);
                    $update_sql = $connec->query('UPDATE videos SET image = ? WHERE id = ?', $imageName, $id);
                    unlink("Images/".$oldImage);
                    $connec->close();
                    $log = "2361\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe lesson: '".$select_sqli['caption']."' with an id: '".$id."' image is updated successfully \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: index?editIerror=0");
                    exit;
				}else{
				    $log = "3362\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to update the image of the lesson that has an id: '".$id."' with Image: '".$fileName."' that has an unallowed extension, and Image size: '".$fileSize."' \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: lessonsEditImg?editIerror=3&id=$id");
                    exit;
				}
			}else{
			    $log = "3363\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to edit the image of the lesson that has id: '".$id."' but Image: '".$fileName."' with size: '".$fileSize."' could not be uploaded \n";
                file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                header("location: lessonsEditImg?editIerror=1&id=$id");
                exit;
			}
		}
    }else{
        $connec->close();
        $log = "4253\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to edit the image of the lesson with an id: '".$_POST['id']."' \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>