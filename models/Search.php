<?php
require_once("SearchFactory.php");
require_once("Interest.php");

class Search {
	# Instance Variables
	private $searchID;
	private $term;
	private $interestID;
	private $dateCreated;
	
	# Data Methods
	public function __construct() {
		$this->searchID = 0;
		$this->term = "";
		$this->metaID = 0;
		$this->metaType = "";
		$this->dateCreated = 0;
	}
	
	public function load($dataArray) {
		$this->searchID = $dataArray['searchID'];
		$this->term = $dataArray['term'];
		$this->interestID = $dataArray['interestID'];
		$this->dateCreated = $dataArray['dateCreated'];
	}
	
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;

		$mysqli = DBConn::mysqli_connect();
		if($this->getSearchID() != 0) {
			// Update an existing search
			$queryString = "update searches
							   set searches.term = '".DBConn::clean_for_mysql($this->getTerm())."',
								   searches.interest_id = ".$this->getInterestID().",
							 where searches.id = ".$this->getSearchID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new search
			$queryString = "insert into searches
							values (0,
									'".DBConn::clean_for_mysql($this->getTerm())."',
									".$this->getInterestID().",
									NOW())";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->searchID = $mysqli->insert_id;
		}
	}

	
	# Getters
	public function getSearchID() { return $this->searchID; }
	
	public function getTerm() { return $this->term; }
	
	public function getInterestID() { return $this->interestID; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	# Setters
	public function setTerm($str) { $this->term = $str; }
	
	public function setInterestID($int) { $this->interestID = $int; }
}

?>