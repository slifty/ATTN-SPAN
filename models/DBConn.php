<?php
include_once("conf.php");
class DBConn {
	private static $dbConnection = null;
	public static function mysqli_connect() {
		global $MYSQLHOST, $MYSQLUSER, $MYSQLPASS, $MYSQLDB;
		// If a connection exists, return it
		if(DBConn::$dbConnection != null)
			return DBConn::$dbConnection;
		
		// Create a connection
		DBConn::$dbConnection = new mysqli($MYSQLHOST,$MYSQLUSER,$MYSQLPASS,$MYSQLDB);
		
		return DBConn::$dbConnection;
	}
	public static function clean_for_mysql($string) {   
		$string = ltrim($string);       
		$string = rtrim($string);
		$string = htmlentities($string);
		$string = nl2br($string, true);
		
		$dbConn = DBConn::mysqli_connect();
		$string = $dbConn->real_escape_string($string);
		return $string;
	}
}
?>