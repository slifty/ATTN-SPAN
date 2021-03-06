<?php
require_once("DBConn.php");
require_once("Episode.php");
class EpisodeFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the object data
		$queryString = "select episodes.id as episodeID,
							   episodes.user_id as userID,
							   episodes.title as title,
							   episodes.thumbnail as thumbnail,
							   unix_timestamp(episodes.based_date) as dateBased,
							   unix_timestamp(episodes.creation_date) as dateCreated
						  from episodes
						 where episodes.id = ".$objectID;
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0) {
			$result->free();
			return new Episode();
		}
		
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['episodeID'] = $resultArray['episodeID'];
		$dataArray['userID'] = $resultArray['userID'];
		$dataArray['title'] = $resultArray['title'];
		$dataArray['thumbnail'] = $resultArray['thumbnail'];
		$dataArray['dateBased'] = $resultArray['dateBased'];
		$dataArray['dateCreated'] = $resultArray['dateCreated'];
		
		$newObject = new Episode();
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
		$queryString = "select episodes.id as episodeID,
							   episodes.user_id as userID,
							   episodes.title as title,
							   episodes.thumbnail as thumbnail,
							   unix_timestamp(episodes.based_date) as dateBased,
							   unix_timestamp(episodes.creation_date) as dateCreated
						  from episodes
						 where episodes.id IN (".$objectIDString.")
					  order by episodes.based_date desc";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['episodeID'] = $resultArray['episodeID'];
			$dataArray['userID'] = $resultArray['userID'];
			$dataArray['title'] = $resultArray['title'];
			$dataArray['thumbnail'] = $resultArray['thumbnail'];
			$dataArray['dateBased'] = $resultArray['dateBased'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Episode();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>