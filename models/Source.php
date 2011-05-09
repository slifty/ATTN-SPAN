<?php
require_once("SourceFactory.php");
class Source {
	
	# Instance Variables
	private $sourceID;
	private $url;
	
	# Data Methods
	public function __construct() {
		$this->sourceID = 0;
		$this->url = "";
	}
	
	public function load($dataArray) {
		$this->sourceID = $dataArray['sourceID'];
		$this->url = $dataArray['url'];
	}
	
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;
		
		$mysqli = DBConn::mysqli_connect();
		if($this->getSourceID() != 0) {
			// Update an existing source
			$queryString = "UPDATE sources
							   set sources.url = '".DBConn::clean_for_mysql($this->getURL())."'
							 where sources.id = ".$this->getSourceID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new interest
			$queryString = "INSERT into sources
							values (0,
									'".DBConn::clean_for_mysql($this->getURL())."')";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->sourceID = $mysqli->insert_id;
		}
	}

	
	# Getters
	public function getSourceID() { return $this->sourceID; }
	
	public function getURL() { return $this->url; }
	
	
	# Setters
	public function setURL($str) { $this->url = $str; }
	
	
	# Static Methods
	public static function getSourceByURL($url) {
		$mysqli = DBConn::mysqli_connect();
		$queryString = "select sources.id as sourceID
						  from sources
						 where sources.url = '".DBConn::clean_for_mysql($url)."'";
					
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		if($result->num_rows == 0)
			return null;
		
		$resultArray = $result->fetch_assoc();
		$sourceID = $resultArray['sourceID'];
		return SourceFactory::getObject($sourceID);
	}
}

?>