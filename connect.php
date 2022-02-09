<?php
    function get_client_ip(){
        $str="Unknown";
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); 
                                
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        $str = $key." is ".$ip."    ";;
                    }
                }
            }
        }
        return $str;
    }
    
    $ip = get_client_ip();
    date_default_timezone_set("Africa/Cairo");
    $currenttime = date('Y-m-d H:i:s');
    
    class con {
        protected $connection;
        protected $query;
        protected $show_errors = TRUE;
        protected $query_closed = TRUE;
        public $query_count = 0;
        public $ip;
        public $fileName;

        public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = 'class', $charset = 'utf8') {
            $this->ip = get_client_ip();
            if(is_dir('Logs')){
                $this->fileName = "./Logs/SQL_log_".date("Y").".log";
            }elseif(is_dir('../Logs')){
                $this->fileName = '../Logs/SQL_log_'.date("Y").'.log';
            }
            $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
            if ($this->connection->connect_error) {
                $log = "4001\tError \t".$this->ip." \t".date('Y-m-d H:i:s')." \tFailed to connect to the Database -  ". $this->connection->connect_error."\n";
                file_put_contents($this->fileName, $log, FILE_APPEND);
                $this->error('Failed to connect to the Database - ' . $this->connection->connect_error);
            }
            
            $this->connection->set_charset($charset);
            $log = "2001\tInformation \t".$this->ip." \t".date('Y-m-d H:i:s')." \tConnection established successfully with database \n";
            file_put_contents($this->fileName, $log, FILE_APPEND);
        }

        public function query($query) {
            if (!$this->query_closed) {
                $this->query->close();
            }
            if ($this->query = $this->connection->prepare($query)) {
                if (func_num_args() > 1) {
                    $x = func_get_args();
                    $args = array_slice($x, 1);
                    $types = '';
                    $args_ref = array();
                    foreach ($args as $k => &$arg) {
                        if (is_array($args[$k])) {
                            foreach ($args[$k] as $j => &$a) {
                                $types .= $this->_gettype($args[$k][$j]);
                                $args_ref[] = &$a;
                            }
                        } else {
                            $types .= $this->_gettype($args[$k]);
                            $args_ref[] = &$arg;
                        }
                    }
                    array_unshift($args_ref, $types);
                    call_user_func_array(array($this->query, 'bind_param'), $args_ref);
                }
                $this->query->execute();
                if ($this->query->errno) {
                    $log = "4003\tError \t".$this->ip." \t".date('Y-m-d H:i:s')." \tUnable to process query (check your params) -  ". $this->query->error."\n";
                    file_put_contents($this->fileName, $log, FILE_APPEND);
                    $this->error('Unable to process query (check your params) - ' . $this->query->error);
                }
                $this->query_closed = FALSE;
                $this->query_count++;
                $log = "2003\tInformation \t".$this->ip." \t".date('Y-m-d H:i:s')." \tThe '".$query."' query executed successfully \n";
                file_put_contents($this->fileName, $log, FILE_APPEND);
            } else {
                $log = "4002\tError \t".$this->ip." \t".date('Y-m-d H:i:s')." \tUnable to prepare statement (check your syntax) -  ". $this->connection->error."\n";
                file_put_contents($this->fileName, $log, FILE_APPEND);
                $this->error('Unable to prepare statement (check your syntax) - ' . $this->connection->error);
            }
            return $this;
        }


        public function fetchAll($callback = null) {
            $params = array();
            $row = array();
            $meta = $this->query->result_metadata();
            while ($field = $meta->fetch_field()) {
                $params[] = &$row[$field->name];
            }
            call_user_func_array(array($this->query, 'bind_result'), $params);
            $result = array();
            while ($this->query->fetch()) {
                $r = array();
                foreach ($row as $key => $val) {
                    $r[$key] = $val;
                }
                if ($callback != null && is_callable($callback)) {
                    $value = call_user_func($callback, $r);
                    if ($value == 'break') break;
                } else {
                    $result[] = $r;
                }
            }
            $this->query->close();
            $this->query_closed = TRUE;
            return $result;
        }

        public function fetchArray() {
            $params = array();
            $row = array();
            $meta = $this->query->result_metadata();
            while ($field = $meta->fetch_field()) {
                $params[] = &$row[$field->name];
            }
            call_user_func_array(array($this->query, 'bind_result'), $params);
            $result = array();
            while ($this->query->fetch()) {
                foreach ($row as $key => $val) {
                    $result[$key] = $val;
                }
            }
            $this->query->close();
            $this->query_closed = TRUE;
            return $result;
        }

        public function close() {
            $log = "2002\tInformation \t".$this->ip." \t".date('Y-m-d H:i:s')." \tConnection closed successfully with database \n";
            file_put_contents($this->fileName, $log, FILE_APPEND);
            return $this->connection->close();
        }

        public function numRows() {
            $this->query->store_result();
            return $this->query->num_rows;
        }

        public function affectedRows() {
            return $this->query->affected_rows;
        }

        public function lastInsertID() {
            return $this->connection->insert_id;
        }

        public function error($error) {
            if ($this->show_errors) {
                $log = "4004\tError \t".$this->ip." \t".date('Y-m-d H:i:s')." \t". $error."\n";
                file_put_contents($this->fileName, $log, FILE_APPEND);
                exit($error);
            }
        }

        private function _gettype($var) {
            if (is_string($var)) return 's';
            if (is_float($var)) return 'd';
            if (is_int($var)) return 'i';
            return 'b';
        }
    }
?>