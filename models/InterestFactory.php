<?php
require_once("DBConn.php");
require_once("Interest.php");
class InterestFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the object data
		$queryString = "select interests.id as interestID,
							   interests.name as name,
							   interests.code as code,
							   interests.type as type,
							   interests.parent_id as parentID
						  from interests
						 where interests.id = ".$objectID;
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0) {
			$result->free();
			return new User();
		}
		
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['interestID'] = $resultArray['interestID'];
		$dataArray['name'] = $resultArray['name'];
		$dataArray['code'] = $resultArray['code'];
		$dataArray['type'] = $resultArray['type'];
		$dataArray['parentID'] = $resultArray['parentID'];
		
		$newObject = new Interest();
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
		$queryString = "select interests.id as interestID,
							   interests.name as name,
							   interests.code as code,
							   interests.type as type,
							   interests.parent_id as parentID
						  from interests
						 where interests.id IN (".$objectIDString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['interestID'] = $resultArray['interestID'];
			$dataArray['name'] = $resultArray['name'];
			$dataArray['code'] = $resultArray['code'];
			$dataArray['type'] = $resultArray['type'];
			$dataArray['parentID'] = $resultArray['parentID'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Interest();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>