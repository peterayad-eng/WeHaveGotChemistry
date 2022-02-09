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
        
        $select_sql = $connec->query('SELECT * FROM videos')->fetchAll();
        foreach($select_sql as $video){
                if($caption == $video['caption']){
                    if($id == $video['id']){
                        continue;
                    }else{
                        $repeatflag=$repeatflag+1;
                    }
                }
        }

        if($repeatflag == 0){
            $update_sql = $connec->query('UPDATE videos SET url = ?, level = ?, caption = ? WHERE id = ?', $url, $level, $caption, $id);
            $connec->close();
            $log = "2351\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe lesson: '".$caption."' with an id: '".$id."' data is updated successfully \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: index?editDerror=0");
            exit;
        }else{
            $connec->close();
            $log = "3351\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe lesson: '".$caption."' with an id: '".$id."' data could not be updated because the targeted label is already exist \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: lessonsEditData?editDerror=2&id=$id");
            exit;
        }
    }else{
        $connec->close();
        $log = "4252\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to edit data of the lesson: '".$_POST['caption']."' with an id: '".$_POST['id']."' \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>