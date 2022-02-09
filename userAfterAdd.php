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
		
	  	$newstudent = test_input($_POST['user']);
		$pass = test_input($_POST['pass']);
        $cpass = test_input($_POST['cpass']);
        $level = test_input($_POST['level']);
        $type = "student";
        $repeatflag=0;

		$rows = $connec->query('SELECT * FROM users')->numRows();
        if($rows==0){
            $Addedid = 0;
        }else{
            $last_row = $connec->query('SELECT * FROM users ORDER BY id DESC LIMIT 1')->fetchArray();
            $Addedid = $last_row['id'] + 1;
        }
        
        $select_sql = $connec->query('SELECT * FROM users')->fetchAll();
        foreach($select_sql as $student){
                if($newstudent == $student['user']){
                    $repeatflag=$repeatflag+1;
                }
        }
        
        if($repeatflag == 0){
            if ($pass == $cpass){
                $password = password_hash($pass, PASSWORD_DEFAULT);
                $insert_sql = $connec->query('INSERT INTO users (id, user, pass, type, level) VALUES (?,?,?,?,?)', $Addedid, $newstudent, $password, $type, $level);
                $connec->close();
                $log = "2281\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has added a student: '".$newstudent."' with an id: '".$Addedid."' successfully \n";
                file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                header("location: users?adderror=0");
            }else{
                $connec->close();
                $log = "3281\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has failed to add a student: '".$newstudent."' that has an id: '".$Addedid."' because the confirm password did not match \n";
                file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                header("location: usersAdd?adderror=2");
                exit;
            }
        }else{
            $connec->close();
            $log = "4281\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add a student: '".$newstudent."' which is already exist \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: usersAdd?adderror=2");
            exit;
        }
    }else{
        $connec->close();
        $log = "4251\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to add a student \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>