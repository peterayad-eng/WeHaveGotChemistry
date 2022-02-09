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
    $selectu_sql = $connec->query('SELECT id, type, level FROM users WHERE user = ?', $user)->fetchArray();
    $student_id = $selectu_sql['id'];
    $type = $selectu_sql['type'];
    $level = $selectu_sql['level'];

    $fileExtensions=['pdf','doc','docx','xls','xlsx','ppt','pptx'];
    $fileName = $_FILES['answer']['name'];
    $fileSize = $_FILES['answer']['size'];
    $fileTmpName  = $_FILES['answer']['tmp_name'];
    $fileType = $_FILES['answer']['type'];
    $tmp = explode('.', $fileName);
    $fileExtension = strtolower(end($tmp));
    $repeatflag=0;
    	
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
		
    define("SIZE", 52428800);
    $homework_id = $_POST['homework'];
    $selecth_sql = $connec->query('SELECT * FROM homework WHERE id = ?', $homework_id)->fetchArray();
    $homework = $selecth_sql['title'];
    $title = $user.'-'.$homework;
    
    if(array_key_exists('answer', $_FILES)){
        if ($_FILES['answer']['error'] === UPLOAD_ERR_OK) {
            if($fileSize>SIZE){
                $connec->close();
                $log = "3321\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe student has tried to add an oversized answer document: '".$fileName."', and document size: '".$fileSize."' for the homework: '".$homework."' \n";
                file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                header("location: answersAdd?editLerror=2");
                exit;
            }

            if (in_array($fileExtension,$fileExtensions)){
                $docName = basename($fileName);
                $select_sql = $connec->query('SELECT * FROM answers')->fetchAll();
                
                do{
                    $editflag=0;
                    foreach($select_sql as $answer){
                        if ($docName == $answer['url']){
                            $docName = "a".$docName;
                            $editflag=1;
                        }
                    }
                }while($editflag==1);
                
                $sitepath= "answers/".$docName;
                $uploadpath= "answers/".$docName;
                $upload= move_uploaded_file($fileTmpName, $uploadpath);
                $row = $connec->query('SELECT * FROM answers WHERE student_id = ? AND homework_id = ?', $student_id, $homework_id)->numRows();
                
                if($row==0){
                    $rows = $connec->query('SELECT * FROM answers')->numRows();
                    if($rows==0){
                        $Addedid = 0;
                    }else{
                        $last_row = $connec->query('SELECT * FROM answers ORDER BY id DESC LIMIT 1')->fetchArray();
                        $Addedid = $last_row['id'] + 1;
                    }
                    $insert_sql = $connec->query('INSERT INTO answers (id, title, url, level, student_id, homework_id) VALUES (?,?,?,?,?,?)', $Addedid, $title, $docName, $level, $student_id, $homework_id);
                }else{
                    $selecta_sql = $connec->query('SELECT * FROM answers WHERE student_id = ? AND homework_id = ?', $student_id, $homework_id)->fetchArray();
                    $updated_id = $selecta_sql['id'];
                    $old_url = "answers/".$selecta_sql['url'];
                    $update_sql = $connec->query('UPDATE answers SET url = ? WHERE id = ?', $docName, $updated_id);
                    unlink($old_url);
                }
                
                $connec->close();
                $log = "2301\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe student has added an answer document: '".$docName."' for the homework: '".$homework."' successfully \n";
                file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                header("location: homeworks?adderror=0");
            }else{
                $connec->close();
                $log = "3322\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe student has tried to add an answer document: '".$fileName."' that has an unallowed extension, and document size: '".$fileSize."' \n";
                file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                header("location: answersAdd?editLerror=3");
                exit;
            }
        } else {
            $connec->close();
            $log = "3323\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe student has tried to add an answer document: '".$fileName."' with size: '".$fileSize."' could not be uploaded \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: answersAdd?adderror=1");
            exit;
        }
    }
?>