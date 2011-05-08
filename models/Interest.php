<?php
require_once("InterestFactory.php");
require_once("SearchFactory.php");
class Interest {
	
	# Class Contants
	const REGION = 'r';
	const TOPIC = 't';
	
	# Instance Variables
	private $interestID;
	private $name;
	private $code;
	private $type;
	private $parentID;
	
	# Data Methods
	public function __construct() {
		$this->interestID = 0;
		$this->name = "";
		$this->code = "";
		$this->type = "";
		$this->parentID = 0;
	}
	
	public function load($dataArray) {
		$this->interestID = $dataArray['interestID'];
		$this->name = $dataArray['name'];
		$this->code = $dataArray['code'];
		$this->type = $dataArray['type'];
		$this->parentID = $dataArray['parentID'];
	}
	
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;
		
		$mysqli = DBConn::mysqli_connect();
		if($this->getInterestID() != 0) {
			// Update an existing interest
			$queryString = "UPDATE interests
							   set interests.name = '".DBConn::clean_for_mysql($this->getName())."',
								   interests.code = '".DBConn::clean_for_mysql($this->getCode())."',
								   interests.type = '".DBConn::clean_for_mysql($this->getType())."',
								   interests.parentID = ".DBConn::clean_for_mysql($this->getParentID())."
							 where interests.id = ".$this->getInterestID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new interest
			$queryString = "INSERT into interests
							values (0,
									'".DBConn::clean_for_mysql($this->getName())."',
									'".DBConn::clean_for_mysql($this->getCode())."',
									'".DBConn::clean_for_mysql($this->getType())."',
									".$this->getParentID().")";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->interestID = $mysqli->insert_id;
		}
	}

	
	# Getters
	public function getInterestID() { return $this->interestID; }
	
	public function getName() { return $this->name; }
	
	public function getCode() { return $this->code; }
	
	public function getType() { return $this->type; }
	
	public function getParentID() { return $this->parentID; }
	
	public function getSearches() {
		$mysqli = DBConn::mysqli_connect();
		$queryString = "select searches.id as searchID
						  from searches
						 where searches.interest_id = ".$this->getInterestID();
		
		$result = $mysqli->query($queryString);
		$searchIDs = array();
		while($resultArray = $result->fetch_assoc())
			$searchIDs[] = $resultArray['searchID'];
		
		return SearchFactory::getObjects($searchIDs);
	}
	
	# Setters
	public function setName($str) { $this->name = $str; }
	
	public function setCode($str) { $this->code = $str; }
	
	public function setType($chr) { $this->type = $chr; }
	
	public function setParentID($int) { $this->parentID = $int;}
	
	
	# Static Methods
	public static function getInterests($interestType = '') {
		$mysqli = DBConn::mysqli_connect();
		$queryString = "select interests.id as interestID
						  from interests
						 where interests.type = '".DBConn::clean_for_mysql($interestType)."'
						    or '' = '".DBConn::clean_for_mysql($interestType)."'
					  order by interests.name";
					
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		$interestIDs = array();
		while($resultArray = $result->fetch_assoc())
			$interestIDs[] = $resultArray['interestID'];
		
		return InterestFactory::getObjects($interestIDs);
	}
}

?>