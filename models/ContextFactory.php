<?php
require_once("DBConn.php");
require_once("Context.php");
class ContextFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		$this->contextID = $dataArray['contextID'];
		$this->sourceID = $dataArray['sourceID'];
		$this->time = $dataArray['time'];
		$this->title = $dataArray['title'];
		$this->url = $dataArray['url'];
		$this->description = $dataArray['description'];
		$this->dateCreated = $dataArray['dateCreated'];
		// Load the object data
		$queryString = "select contexts.id as contextID,
							   contexts.source_id as sourceID,
							   contexts.time as time,
							   contexts.title as title,
							   contexts.url as url,
							   contexts.description as description,
							   contexts.creation_date as dateCreated
						  from contexts
						 where contexts.id = ".$objectID;
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0) {
			$result->free();
			return new Clip();
		}
		
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['contextID'] = $resultArray['contextID'];
		$dataArray['sourceID'] = $resultArray['sourceID'];
		$dataArray['time'] = $resultArray['time'];
		$dataArray['title'] = $resultArray['title'];
		$dataArray['url'] = $resultArray['url'];
		$dataArray['description'] = $resultArray['description'];
		$dataArray['dateCreated'] = $resultArray['dateCreated'];
		
		$newObject = new Context();
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
		$queryString = "select contexts.id as contextID,
							   contexts.source_id as sourceID,
							   contexts.time as time,
							   contexts.title as title,
							   contexts.url as url,
							   contexts.description as description,
							   contexts.creation_date as dateCreated
						  from contexts
						 where contexts.id IN (".$objectIDString.")
					  order by contexts.time";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['contextID'] = $resultArray['contextID'];
			$dataArray['sourceID'] = $resultArray['sourceID'];
			$dataArray['time'] = $resultArray['time'];
			$dataArray['title'] = $resultArray['title'];
			$dataArray['url'] = $resultArray['url'];
			$dataArray['description'] = $resultArray['description'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Context();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>