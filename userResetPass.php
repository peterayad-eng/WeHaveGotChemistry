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
	  	function test_input($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$password = test_input($_POST['pass']);
        $cpassword = test_input($_POST['cpass']);
        $id = $_POST['id'];
        
		if ($password == $cpassword){
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = $connec->query('UPDATE users SET pass = ? WHERE id = ?', $pass, $id);
            $connec->close();
            $log = "2261\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe password has been reset successfully for the user that has an id: '".$id."' \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: users?editPerror=0");
            exit;
        }else{
            $connec->close();
            $log = "3261\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has failed to reset the password while changing the password for the user that has an id: '".$id."' because the confirm password did not match \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: userEditPass?editPerror=2&id=$id");
            exit;
        }
    }else{
        $connec->close();
        $log = "4251\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to reset password of the student that has an id: '".$_POST['id']."' \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
