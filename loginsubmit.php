<?php
	require_once "connect.php";	

	  	function test_input($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$user = test_input($_POST['user']);
		$password = test_input($_POST['pass']);

        define("TIME", 300);
        define("TIMEAN", 7200);
        date_default_timezone_set("Africa/Cairo");
        $currenttime = date('Y-m-d H:i:s');
		
		$connec = new con();
		$conn = $connec->connect();

        $selectan_sql = "SELECT * FROM anonymous WHERE id = 1";
        $resultan = mysqli_query($conn, $selectan_sql);
        $rowan = $resultan->fetch_assoc();
        $antime = $rowan['timestamp'];
        $anattempt = $rowan['anattempt'];
        
        $timesuban = strtotime($currenttime) - strtotime($antime);
		
		$select_sql = "SELECT * FROM users WHERE user = '{$user}'";
		$result = mysqli_query($conn, $select_sql);
        $row = $result->fetch_assoc();
        $usertime = $row['timestamp'];
        $userattempt = $row['attempts'];
        $timesubuser = strtotime($currenttime) - strtotime($usertime);
		if($result->num_rows != 0){
			if($row['pass'] == $password){
                if($userattempt > 4 && $timesubuser < TIME){
                    $connec->disconnect($conn);
                    header("location: page-login.php?error=2");
                }else{
                    $userattempt = 0;
                    $update_sql = "UPDATE users SET timestamp = '{$currenttime}', attempts = '{$userattempt}' WHERE user = '{$user}'";
                    $update = mysqli_query($conn, $update_sql);
                    if(isset($_POST['remember']) && $_POST['remember'] == 'true')
                    {
                        setcookie('user', $user, time() + 180);
                    }
                    session_start();
                    $_SESSION['user'] = $user;
                    $connec->disconnect($conn);
                    header("location: index.php");
                }
			}else{
                if($userattempt > 4 && $timesubuser < TIME){
                    $connec->disconnect($conn);
                    header("location: page-login.php?error=2");
                }else if ($userattempt > 4 && $timesubuser > TIME){
                    $userattempt = 1;
                    $update_sql = "UPDATE users SET timestamp = '{$currenttime}', attempts = '{$userattempt}' WHERE user = '{$user}'";
                    $update = mysqli_query($conn, $update_sql);
                    $connec->disconnect($conn);
                    header("location: page-login.php?error=1");
                }elseif ($userattempt <= 4 && $timesubuser > TIME){
                    $userattempt = 1;
                    $update_sql = "UPDATE users SET timestamp = '{$currenttime}', attempts = '{$userattempt}' WHERE user = '{$user}'";
                    $update = mysqli_query($conn, $update_sql);
                    $connec->disconnect($conn);
                    header("location: page-login.php?error=1");
                }else{
                    $userattempt = $userattempt + 1;
                    $updateuser_sql = "UPDATE users SET timestamp = '{$currenttime}', attempts = '{$userattempt}' WHERE user = '{$user}'";
                    $updateuser = mysqli_query($conn, $updateuser_sql);
                    $connec->disconnect($conn);
                    header("location: page-login.php?error=1");
                }
			}	
		}else{
            if($anattempt > 4 && $timesuban < TIMEAN){
                $connec->disconnect($conn);
                header("location: page-login.php?error=2");
            }else if ($anattempt > 4 && $timesuban > TIMEAN){
                $anattempt = 1;
                $updatean_sql = "UPDATE anonymous SET timestamp = '{$currenttime}', anattempt = '{$anattempt}' WHERE id = 1";
                $updatean = mysqli_query($conn, $updatean_sql);
                $connec->disconnect($conn);
                header("location: page-login.php?error=1");
            }elseif ($anattempt <= 4 && $timesuban > TIMEAN){
                $anattempt = 1;
                $updatean_sql = "UPDATE anonymous SET timestamp = '{$currenttime}', anattempt = '{$anattempt}' WHERE id = 1";
                $updatean = mysqli_query($conn, $updatean_sql);
                $connec->disconnect($conn);
                header("location: page-login.php?error=1");
            }else{
                $anattempt = $anattempt + 1;
                $updatean_sql = "UPDATE anonymous SET timestamp = '{$currenttime}', anattempt = '{$anattempt}' WHERE id = 1";
                $updatean = mysqli_query($conn, $updatean_sql);
                $connec->disconnect($conn);
                header("location: page-login.php?error=1");
            }
		}
?>
