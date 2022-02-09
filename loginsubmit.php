<?php
    require_once "connect.php";	

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
		
    $robotest = $_POST['verifyingcode'];
    $ip = $_POST['ip'];

    if($robotest){
        $log = "3201\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$_POST['user']." \tA robot has tried to log in to the admin panel with Password: '".$_POST['pass']."', and Verifying Code: '".$robotest."' \n";
        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
        header("location: page-login?error=1");
        exit;
    }else{
        $user = test_input(filter_var($_POST['user'], FILTER_SANITIZE_STRING));
        $password = test_input(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));
            
        define("TIME", 1800);
        define("TIMEAN", 7200);
        date_default_timezone_set("Africa/Cairo");
        $currenttime = date('Y-m-d H:i:s');

        $connec = new con();
            
        function validate_user($anattempt){
            global $connec, $user, $password, $ip, $currenttime;
            $select_sql = $connec->query('SELECT * FROM users WHERE user = ?', $user)->fetchArray();
            $usertime = $select_sql['timestamp'];
            $userattempt = $select_sql['attempts'];
            $timesubuser = strtotime($currenttime) - strtotime($usertime);
            if(password_verify($password, $select_sql['pass'])){
                if($userattempt > 4 && $timesubuser < TIME){
                    $connec->close();
                    $log = "3202\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the right password but after more than 5 attempts \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: page-login?error=2");
                    exit;
                }else{
                    $userattempt = 0;
                    $update_sql = $connec->query('UPDATE users SET timestamp = ?, attempts = ? WHERE user = ?', $currenttime, $userattempt, $user);
                    $update_sqlan = $connec->query('UPDATE anonymous SET timestamp = ?, anattempt = ? WHERE ip = ?', $currenttime, $userattempt, $ip);
                    $log = "2201\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has logged in successfully \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    if(isset($_POST['remember']) && $_POST['remember'] == 'true'){
                        setcookie('user', $user, time() + 180);
                    }
                    session_start();
                    $_SESSION['user'] = $user;
                    $connec->close();
                    header("location: index");
                    exit;
                }
            }else{
                if($userattempt > 4 && $timesubuser < TIME){
                    $connec->close();
                    $log = "4203\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the wrong password: '".$password."', and the number of attempts has exceeded the limit \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: page-login?error=2");
                    exit;
                }elseif ($userattempt > 4 && $timesubuser > TIME){
                    $userattempt = 1;
                    $update_sql = $connec->query('UPDATE users SET timestamp = ?, attempts = ? WHERE user = ?', $currenttime, $userattempt, $user);
                    $update_sqlan = $connec->query('UPDATE anonymous SET timestamp = ?, anattempt = ? WHERE ip = ?', $currenttime, $anattempt, $ip);
                    $connec->close();
                    $log = "4204\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the wrong password: '".$password."' after the ban time is over \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: page-login?error=1");
                    exit;
                }elseif ($userattempt <= 4 && $timesubuser > TIME){
                    $userattempt = 1;
                    $update_sql = $connec->query('UPDATE users SET timestamp = ?, attempts = ? WHERE user = ?', $currenttime, $userattempt, $user);
                    $update_sqlan = $connec->query('UPDATE anonymous SET timestamp = ?, anattempt = ? WHERE ip = ?', $currenttime, $anattempt, $ip);
                    $connec->close();
                    $log = "4201\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the wrong password: '".$password."' \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: page-login?error=1");
                    exit;
                }else{
                    $userattempt = $userattempt + 1;
                    $update_sql = $connec->query('UPDATE users SET timestamp = ?, attempts = ? WHERE user = ?', $currenttime, $userattempt, $user);
                    $update_sqlan = $connec->query('UPDATE anonymous SET timestamp = ?, anattempt = ? WHERE ip = ?', $currenttime, $anattempt, $ip);
                    $connec->close();
                    $log = "4202\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the wrong password: '".$password."'and the number of attempts is ".$userattempt." \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: page-login?error=1");
                    exit;
                }
            }
        }
            
        $check_ip = $connec->query('SELECT * FROM anonymous WHERE ip = ?', $ip)->numRows();
        $check_user = $connec->query('SELECT * FROM users WHERE user = ?', $user)->numRows();
            
        if($check_ip){
            $select_sql = $connec->query('SELECT * FROM anonymous WHERE ip = ?', $ip)->fetchArray();
            $antime = $select_sql['timestamp'];
            $anattempt = $select_sql['anattempt'];
            $timesuban = strtotime($currenttime) - strtotime($antime);
                
            if($anattempt > 4 && $timesuban < TIMEAN){
                $connec->close();
                if($check_user){
                    $log = "4223\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in but the number of attempts has exceeded the limit \n";
                }else{
                    $log = "4223\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tAnonymous user has tried to log in with password: '".$password."', and the number of attempts has exceeded the limit \n";
                }
                file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                header("location: page-login?error=2");
                exit;
            }else if ($anattempt > 4 && $timesuban > TIMEAN){
                $anattempt = 1;
                if($check_user){
                    validate_user($anattempt);
                }else{
                    $update_sqlan = $connec->query('UPDATE anonymous SET timestamp = ?, anattempt = ? WHERE ip = ?', $currenttime, $anattempt, $ip);
                    $connec->close();
                    $log = "4224\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tAnonymous user has tried to log in with password: '".$password."' after the ban time is over \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: page-login?error=1");
                    exit;
                }
            }elseif ($anattempt <= 4 && $timesuban > TIMEAN){
                $anattempt = 1;
                if($check_user){
                    validate_user($anattempt);
                }else{
                    $update_sqlan = $connec->query('UPDATE anonymous SET timestamp = ?, anattempt = ? WHERE ip = ?', $currenttime, $anattempt, $ip);
                    $connec->close();
                    $log = "4221\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tAnonymous user has tried to log in with password: '".$password."' \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: page-login?error=1");
                    exit;
                }
            }else{
                $anattempt = $anattempt + 1;
                if($check_user){
                    validate_user($anattempt);
                }else{
                    $update_sqlan = $connec->query('UPDATE anonymous SET timestamp = ?, anattempt = ? WHERE ip = ?', $currenttime, $anattempt, $ip);
                    $connec->close();
                    $log = "4222\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tAnonymous user has tried to log in with password: '".$password."'and the number of attempts is ".$anattempt." \n";
                    file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                    header("location: page-login?error=1");
                    exit;
                }
            }
        }else{
            $rows = $connec->query('SELECT * FROM anonymous')->numRows();
            if($rows==0){
                $new_id=0;
            }else{
                $last_row = $connec->query('SELECT * FROM anonymous ORDER BY id DESC LIMIT 1')->fetchArray();
                $new_id = $last_row['id'] + 1;
            }
                
            if($check_user){
                $select_sql = $connec->query('SELECT * FROM users WHERE user = ?', $user)->fetchArray();
                $usertime = $select_sql['timestamp'];
                $userattempt = $select_sql['attempts'];
                $timesubuser = strtotime($currenttime) - strtotime($usertime);
                $anattempt = 1;
                if(password_verify($password, $select_sql['pass'])){
                    if($userattempt > 4 && $timesubuser < TIME){
                        $connec->close();
                        $log = "3202\tWarning \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the right password but after more than 5 attempts \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: page-login?error=2");
                        exit;
                    }else{
                        $userattempt = 0;
                        $update_sql = $connec->query('UPDATE users SET timestamp = ?, attempts = ? WHERE user = ?', $currenttime, $userattempt, $user);
                        $insert_sql = $connec->query('INSERT INTO anonymous (id, ip, anattempt, timestamp) VALUES (?,?,?,?)', $new_id, $ip, $userattempt, $currenttime);
                        $log = "2201\tInformation \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has logged in successfully \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        if(isset($_POST['remember']) && $_POST['remember'] == 'true'){
                            setcookie('user', $user, time() + 180);
                        }
                        session_start();
                        $_SESSION['user'] = $user;
                        $connec->close();
                        header("location: index");
                        exit;
                    }
                }else{
                    if($userattempt > 4 && $timesubuser < TIME){
                        $connec->close();
                        $log = "4203\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the wrong password: '".$password."', and the number of attempts has exceeded the limit \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: page-login?error=2");
                        exit;
                    }else if ($userattempt > 4 && $timesubuser > TIME){
                        $userattempt = 1;
                        $update_sql = $connec->query('UPDATE users SET timestamp = ?, attempts = ? WHERE user = ?', $currenttime, $userattempt, $user);
                        $insert_sql = $connec->query('INSERT INTO anonymous (id, ip, anattempt, timestamp) VALUES (?,?,?,?)', $new_id, $ip, $anattempt, $currenttime);
                        $connec->close();
                        $log = "4204\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the wrong password: '".$password."' after the ban time is over \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: page-login?error=1");
                        exit;
                    }elseif ($userattempt <= 4 && $timesubuser > TIME){
                        $userattempt = 1;
                        $update_sql = $connec->query('UPDATE users SET timestamp = ?, attempts = ? WHERE user = ?', $currenttime, $userattempt, $user);
                        $insert_sql = $connec->query('INSERT INTO anonymous (id, ip, anattempt, timestamp) VALUES (?,?,?,?)', $new_id, $ip, $anattempt, $currenttime);
                        $connec->close();
                        $log = "4201\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the wrong password: '".$password."' \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: page-login?error=1");
                        exit;
                    }else{
                        $userattempt = $userattempt + 1;
                        $update_sql = $connec->query('UPDATE users SET timestamp = ?, attempts = ? WHERE user = ?', $currenttime, $userattempt, $user);
                        $insert_sql = $connec->query('INSERT INTO anonymous (id, ip, anattempt, timestamp) VALUES (?,?,?,?)', $new_id, $ip, $anattempt, $currenttime);
                        $connec->close();
                        $log = "4202\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tThe user has tried to log in with the wrong password: '".$password."'and the number of attempts is ".$userattempt." \n";
                        file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                        header("location: page-login?error=1");
                        exit;
                    }
                }	
            }else{
                $anattempt = 1;
                $insert_sql = $connec->query('INSERT INTO anonymous (id, ip, anattempt, timestamp) VALUES (?,?,?,?)', $new_id, $ip, $anattempt, $currenttime);
                $connec->close();
                $log = "4225\tError \t".$ip." \t".date('Y-m-d H:i:s')." \t".$user." \tA new anonymous user has tried to log in with password: '".$password."' \n";
                file_put_contents('./Logs/Web_log_'.date("Y").'.log', $log, FILE_APPEND);
                header("location: page-login?error=1");
                exit;
            }
        }
    }
?>
