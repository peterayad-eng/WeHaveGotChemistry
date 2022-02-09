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
		$fileExtensions=['pdf','doc','docx','xls','xlsx','ppt','pptx'];
		$fileName = $_FILES['url']['name'];
		$fileSize = $_FILES['url']['size'];
		$fileTmpName  = $_FILES['url']['tmp_name'];
		$fileType = $_FILES['url']['type'];
        $tmp = explode('.', $fileName);
		$fileExtension = strtolower(end($tmp));

		function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
		}
		
		define("SIZE", 52428800);
	  	$title = test_input($_POST['title']);
		$level = test_input($_POST['level']);
        $par = test_input($_POST['par']);
        $repeatflag=0;
        
        $rows = $connec->query('SELECT * FROM documents')->numRows();
        if($rows==0){
            $Addedid = 0;
        }else{
            $last_row = $connec->query('SELECT * FROM documents ORDER BY id DESC LIMIT 1')->fetchArray();
            $Addedid = $last_row['id'] + 1;
        }
        
        $select_sql = $connec->query('SELECT * FROM documents')->fetchAll();
        foreach($select_sql as $document){
                if($title == $document['title']){
                    $repeatflag=$repeatflag+1;
                }
        }
    
        if($repeatflag == 0){
            if(array_key_exists('url', $_FILES)){
                if ($_FILES['url']['error'] === UPLOAD_ERR_OK) {
                    if($fileSize>SIZE){
                        $connec->close();
                        $log = "3321\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add an oversized document: '".$fileName."', and document size: '".$fileSize."' \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: documentsAdd?editLerror=2");
                        exit;
                    }

                    if (in_array($fileExtension,$fileExtensions)){
                        $docName = basename($fileName);
                        
                        do{
                            $editflag=0;
                            foreach($select_sql as $document){
                                if ($docName == $document['url']){
                                    $docName = "a".$docName;
                                    $editflag=1;
                                }
                            }
                        }while($editflag==1);
                        
                        $sitepath= "documents/".$docName;
                        $uploadpath= "documents/".$docName;
                        $upload= move_uploaded_file($fileTmpName, $uploadpath);
                        $insert_sql = $connec->query('INSERT INTO documents (id, title, par, url, level) VALUES (?,?,?,?,?)', $Addedid, $title, $par, $docName, $level);
                        $connec->close();
                        $log = "2301\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has added a document: '".$title."' with an id: '".$Addedid."' successfully \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: document?adderror=0");
                    }else{
                        $connec->close();
                        $log = "3322\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add a document: '".$fileName."' that has an unallowed extension, and document size: '".$fileSize."' \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: documentsAdd?editLerror=3");
                        exit;
                    }

                } else {
                    $connec->close();
                    $log = "3323\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add a document: '".$fileName."' with size: '".$fileSize."' could not be uploaded \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: documentsAdd?adderror=1");
                    exit;
                }
            }
        }else{
            $connec->close();
            $log = "3301\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tThe user has tried to add a document: '".$title."' that is already exist \n";
            file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
            header("location: documentsAdd?adderror=2");
            exit;
        }
    }else{
        $connec->close();
        $log = "4251\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_SESSION['user']." \tUnauthorized user has tried to add a document \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        require_once "logout.php";
    }
?>