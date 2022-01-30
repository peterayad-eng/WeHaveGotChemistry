<?php
class con{
	public static $dbname= "maggymah_class";
	public static $hostname= "localhost";
	public static $username= "maggymah_teacher";
	public static $pass= "juliemichael12";
	public function connect() {
		$con = mysqli_connect(self::$hostname, self::$username, self::$pass, self::$dbname);
		if($con == false){
			die(mysqli_connect_error());
		}
		return $con;
	}
	public function disconnect($con) {
		mysqli_close($con);
	}
}
?>