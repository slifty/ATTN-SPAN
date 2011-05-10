<?php
require_once("FlagFactory.php");
class Flag {
	# Instance Variables
	private $flagID;
	private $sourceID;
	private $userID;
	private $time;
	private $type;
	private $dateCreated;
	
	# Data Methods
	public function __construct() {
		$this->flagID = 0;
		$this->sourceID = 0;
		$this->userID = 0;
		$this->time = 0;
		$this->type = "";
		$this->dateCreated = 0;
	}
	
	public function load($dataArray) {
		$this->flagID = $dataArray['flagID'];
		$this->sourceID = $dataArray['sourceID'];
		$this->userID = $dataArray['userID'];
		$this->time = $dataArray['time'];
		$this->type = $dataArray['type'];
		$this->dateCreated = $dataArray['dateCreated'];
	}
	
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;
		
		$mysqli = DBConn::mysqli_connect();
		if($this->getFlagID() != 0) {
			// Update an existing flag
			$queryString = "UPDATE flags
							   set flags.source_id = ".$this->getSourceID().",
							       flags.user_id = ".$this->getUserID().",
							       flags.time = ".$this->getTime().",
								   flags.type = '".DBConn::clean_for_mysql($this->getType())."'
							 where flags.id = ".$this->getFlagID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new flag
			$queryString = "INSERT into flags
							values (0,
									".$this->getSourceID().",
									".$this->getUserID().",
									".$this->getTime().",
									'".DBConn::clean_for_mysql($this->getType())."',
									NOW())";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->clipID = $mysqli->insert_id;
		}
	}

	
	# Getters
	public function getFlagID() { return $this->flagID; }

	public function getSourceID() { return $this->sourceID; }

	public function getUserID() { return $this->userID; }
	
	public function getTime() { return $this->time; }
	
	public function getType() { return $this->type; }
	
	public function getDateCreated() { return $this->dateCreated; }
		
	
	# Setters
	public function setSourceID($int) { $this->sourceID = $int;}

	public function setUserID($int) { $this->userID = $int;}
	
	public function setTime($int) { $this->time = $int;}
	
	public function setType($str) { $this->type = $str;}
}

?>