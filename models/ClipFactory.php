<?php
require_once("DBConn.php");
require_once("Clip.php");
class ClipFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the object data
		$queryString = "select clips.id as clipID,
							   clips.episode_id as episodeID,
							   clips.search_term_id as searchID,
							   clips.clip_order as clipOrder,
							   clips.feed_url as feedURL,
							   clips.context_url as contextURL,
							   clips.start as start,
							   clips.end as end,
							   clips.description as description,
							   clips.title as title
						  from clips
						 where clips.id = ".$objectID;
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0) {
			$result->free();
			return new Clip();
		}
		
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['clipID'] = $resultArray['clipID'];
		$dataArray['episodeID'] = $resultArray['episodeID'];
		$dataArray['searchID'] = $resultArray['searchID'];
		$dataArray['clipOrder'] = $resultArray['clipOrder'];
		$dataArray['feedURL'] = $resultArray['feedURL'];
		$dataArray['contextURL'] = $resultArray['contextURL'];
		$dataArray['start'] = $resultArray['start'];
		$dataArray['end'] = $resultArray['end'];
		$dataArray['description'] = $resultArray['description'];
		$dataArray['title'] = $resultArray['title'];
		
		$newObject = new Clip();
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
		$queryString = "select clips.id as clipID,
							   clips.episode_id as episodeID,
							   clips.search_term_id as searchID,
							   clips.clip_order as clipOrder,
							   clips.feed_url as feedURL,
							   clips.context_url as contextURL,
							   clips.start as start,
							   clips.end as end,
							   clips.description as description,
							   clips.title as title
						  from clips
						 where clips.id IN (".$objectIDString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['clipID'] = $resultArray['clipID'];
			$dataArray['episodeID'] = $resultArray['episodeID'];
			$dataArray['searchID'] = $resultArray['searchID'];
			$dataArray['clipOrder'] = $resultArray['clipOrder'];
			$dataArray['feedURL'] = $resultArray['feedURL'];
			$dataArray['contextURL'] = $resultArray['contextURL'];
			$dataArray['start'] = $resultArray['start'];
			$dataArray['end'] = $resultArray['end'];
			$dataArray['description'] = $resultArray['description'];
			$dataArray['title'] = $resultArray['title'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Clip();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>