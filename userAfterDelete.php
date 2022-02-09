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
        $delete_sql = $connec->query('DELETE FROM users WHERE id = ?', $deletedid);
        
        $log = "2302\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user with an id: '".$deletedid."' is deleted successfully \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        
        $selecta_sql = $connec->query('SELECT * FROM answers WHERE student_id = ?', $deletedid)->fetchAll();
        foreach($selecta_sql as $answer){
            $del_answer = $connec->query('SELECT * FROM answers WHERE id = ?', $answer['id'])->fetchArray();
            $url=$del_answer['url'];
            $url="answers/".$url;

            $delete_answer = $connec->query('DELETE FROM answers WHERE id = ?', $answer['id']);
            unlink($url);
        }
        
        $log = "2305\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tAll answers for the deleted student: '".$deletedid."' has been deleted successfully \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        $connec->close();
        header("location: users?deleteerror=0");
        exit;
    }else{
        $connec->close();
        $log = "4254\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to delete the user with an id: '".$_GET['id']."' \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>