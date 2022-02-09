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
		$deletedid = $_GET['id'];
		
        $select_sql = $connec->query('SELECT * FROM videos WHERE id = ?', $deletedid)->fetchArray();
        $deleted_lesson=$select_sql['caption'];
        $url=$select_sql['image'];
        $url="Images/".$url;
        
        $delete_sql = $connec->query('DELETE FROM videos WHERE id = ?', $deletedid);
        unlink($url);

        $connec->close();
        $log = "2302\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe lesson: '".$deleted_lesson."' with an id: '".$deletedid."' is deleted successfully \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        header("location: index?deleteerror=0");
        exit;
    }else{
        $connec->close();
        $log = "4254\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to delete the lesson with an id: '".$_GET['id']."' \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>