<?php
require_once("DBConn.php");
require_once("Flag.php");
class FlagFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the object data
		$queryString = "select flags.id as flagID,
							   flags.source_id as sourceID,
							   flags.user_id as userID,
							   flags.time as time,
							   flags.type as type,
							   unix_timestamp(flags.creation_date) as dateCreated
						  from flags
						 where flags.id = ".$objectID;
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0) {
			$result->free();
			return new Episode();
		}
		
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['flagID'] = $resultArray['flagID'];
		$dataArray['sourceID'] = $resultArray['sourceID'];
		$dataArray['userID'] = $resultArray['userID'];
		$dataArray['time'] = $resultArray['time'];
		$dataArray['type'] = $resultArray['type'];
		$dataArray['dateCreated'] = $resultArray['dateCreated'];
		
		$newObject = new Flag();
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
		$queryString = "select flags.id as flagID,
							   flags.source_id as sourceID,
							   flags.user_id as userID,
							   flags.time as time,
							   flags.type as type,
							   unix_timestamp(flags.creation_date) as dateCreated
						  from flags
						 where flags.id IN (".$objectIDString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['flagID'] = $resultArray['flagID'];
			$dataArray['sourceID'] = $resultArray['sourceID'];
			$dataArray['userID'] = $resultArray['userID'];
			$dataArray['time'] = $resultArray['time'];
			$dataArray['type'] = $resultArray['type'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Flag();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>