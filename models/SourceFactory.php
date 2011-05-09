<?php
require_once("DBConn.php");
require_once("Source.php");
class SourceFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the object data
		$queryString = "select sources.id as sourceID,
							   sources.url as url
						  from sources
						 where sources.id = ".$objectID;
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0) {
			$result->free();
			return new Source();
		}
		
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['sourceID'] = $resultArray['sourceID'];
		$dataArray['url'] = $resultArray['url'];
		
		$newObject = new Source();
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
		$queryString = "select sources.id as sourceID,
							   sources.url as url
						  from sources
						 where sources.id IN (".$objectIDString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['sourceID'] = $resultArray['sourceID'];
			$dataArray['url'] = $resultArray['url'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Source();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>