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
	  	$editstudent = test_input($_POST['user']);
		$level = test_input($_POST['level']);
        $repeatflag=0;

		$select_sql = $connec->query('SELECT id, user, type, level FROM users')->fetchAll();
        foreach($select_sql as $student){
                if($editstudent == $student['user']){
                    if($id == $student['id']){
                        continue;
                    }else{
                        $repeatflag=$repeatflag+1;
                    }
                }
        }
		
        if($repeatflag == 0){
            $update_sql = $connec->query('UPDATE users SET user = ?, level = ? WHERE id = ?', $editstudent, $level, $id);
            $connec->close();
            $log = "2351\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe student: '".$editstudent."' with an id: '".$id."' data is updated successfully \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: users?editDerror=0");
            exit;
        }else{
            $connec->close();
            $log = "4261\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to edit a student that have an id: '".$id."' with username: '".$editstudent."' which is already exist \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: usersEditData?editDerror=2&id=$id");
            exit;
        }
    }else{
        $connec->close();
        $log = "4251\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to edit a student data \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>