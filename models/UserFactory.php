<?php
require_once("DBConn.php");
require_once("User.php");
class UserFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the object data
		$queryString = "select users.id as userID,
							   users.username as username,
							   unix_timestamp(users.join_date) as dateJoined
						  from users
						 where users.id = ".$objectID;
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0) {
			$result->free();
			return new User();
		}
		
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['userID'] = $resultArray['userID'];
		$dataArray['username'] = $resultArray['username'];
		$dataArray['dateJoined'] = $resultArray['dateJoined'];
		
		$newObject = new User();
		$newObject->load($dataArray);
		return $newObject;
	}
	
	public static function getObjects($objects) {
		$objectIDs = array();
		foreach($objects as $object)
			$objectIDs[] = (int)$object;
		
		// If there are no objects being request, return an empty array
		if(sizeof($objectIDs) == 0)
			return array();
		
		$objectIDString = implode(",",$objectIDs);
		$mysqli = DBConn::mysqli_connect();
		$dataArrays = array();
		
		// Load the object data
		$queryString = "select users.id as userID,
							   users.username as username,
							   users.password as password,
							   users.salt as salt,
							   unix_timestamp(users.join_date) as dateJoined
						  from users
						 where users.id IN (".$objectIDString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['userID'] = $resultArray['userID'];
			$dataArray['username'] = $resultArray['username'];
			$dataArray['password'] = $resultArray['password'];
			$dataArray['salt'] = $resultArray['salt'];
			$dataArray['dateJoined'] = $resultArray['dateJoined'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Need();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>