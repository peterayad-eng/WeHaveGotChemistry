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
	  	$title = test_input($_POST['title']);
		$level = test_input($_POST['level']);
        $par = test_input($_POST['par']);
        $repeatflag=0;
        
        $select_sql = $connec->query('SELECT * FROM documents')->fetchAll();
        foreach($select_sql as $document){
                if($title == $document['title']){
                    if($id == $document['id']){
                        continue;
                    }else{
                        $repeatflag=$repeatflag+1;
                    }
                }
        }

        if($repeatflag == 0){
            $update_sql = $connec->query('UPDATE documents SET title = ?, level = ?, par = ? WHERE id = ?', $title, $level, $par, $id);
            $connec->close();
            $log = "2351\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe document: '".$title."' with an id: '".$id."' data is updated successfully \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: document?editDerror=0");
            exit;
        }else{
            $connec->close();
            $log = "3351\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe document: '".$title."' with an id: '".$id."' data could not be updated because the targeted title is already exist \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: documentsEditData?editDerror=2&id=$id");
            exit;
        }
    }else{
        $connec->close();
        $log = "4252\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to edit data of the document: '".$_POST['title']."' with an id: '".$_POST['id']."' \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>