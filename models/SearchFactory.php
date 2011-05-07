<?php
require_once("DBConn.php");
require_once("Search.php");
class SearchFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the object data
		$queryString = "SELECT searches.id as searchID,
							   searches.term as term,
							   searches.interest_id as interestID,
							   searches.creation_date as dateCreated,
						  from searches
						 where searches.id = ".$objectID;
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0) {
			$result->free();
			return new Search();
		}
		
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['searchID'] = $resultArray['searchID'];
		$dataArray['term'] = $resultArray['term'];
		$dataArray['interestID'] = $resultArray['interestID'];
		$dataArray['dateCreated'] = $resultArray['dateCreated'];
		
		$newObject = new Search();
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
		$queryString = "SELECT searches.id as searchID,
							   searches.term as term,
							   searches.interest_id as interestID,
							   searches.creation_date as dateCreated
						  from searches
						 where searches.id IN (".$objectIDString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['searchID'] = $resultArray['searchID'];
			$dataArray['term'] = $resultArray['term'];
			$dataArray['interestID'] = $resultArray['interestID'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Search();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>