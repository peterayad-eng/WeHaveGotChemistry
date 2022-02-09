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
        
        $select_sql = $connec->query('SELECT * FROM documents WHERE id = ?', $deletedid)->fetchArray();
        $deleted_doc=$select_sql['title'];
        $url=$select_sql['url'];
        $url="documents/".$url;
        
		$delete_sql = $connec->query('DELETE FROM documents WHERE id = ?', $deletedid);
        unlink($url);
        
        $connec->close();
        $log = "2302\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe document: '".$deleted_doc."' with an id: '".$deletedid."' is deleted successfully \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        header("location: document?deleteerror=0");
        exit;
    }else{
        $connec->close();
        $log = "4254\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to delete the document with an id: '".$_GET['id']."' \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>