<?php
require_once("ContextFactory.php");
class Context {
	# Instance Variables
	private $contextID;
	private $sourceID;
	private $time;
	private $title;
	private $url;
	private $description;
	private $dateCreated;
	
	# Data Methods
	public function __construct() {
		$this->contextID = 0;
		$this->sourceID = 0;
		$this->time = "";
		$this->title = "";
		$this->url = "";
		$this->description = "";
		$this->dateCreated = 0;
	}
	
	public function load($dataArray) {
		$this->contextID = $dataArray['contextID'];
		$this->sourceID = $dataArray['sourceID'];
		$this->time = $dataArray['time'];
		$this->title = $dataArray['title'];
		$this->url = $dataArray['url'];
		$this->description = $dataArray['description'];
		$this->dateCreated = $dataArray['dateCreated'];
	}
	
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;

		$mysqli = DBConn::mysqli_connect();
		if($this->getContextID() != 0) {
			// Update an existing context
			$queryString = "UPDATE contexts
							   set contexts.sourceID = '".$this->getSourceID()."',
								   contexts.time = ".$this->getTime()."',
								   contexts.title = '".DBConn::clean_for_mysql($this->getTitle())."',
								   contexts.url = '".DBConn::clean_for_mysql($this->getURL())."',
								   contexts.description = '".DBConn::clean_for_mysql($this->getDescription())."'
							 where contexts.id = ".$this->getContextID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new context
			$queryString = "INSERT into contexts
							values (0,
									".$this->getSourceID().",
									".$this->getTime().",
									'".DBConn::clean_for_mysql($this->getTitle())."',
									'".DBConn::clean_for_mysql($this->getURL())."',
									'".DBConn::clean_for_mysql($getDescription)."',
									NOW())";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->userID = $mysqli->insert_id;
		}
	}
	
	
	# Getters
	public function getContextID() { return $this->contextID; }
	
	public function getSourceID() { return $this->sourceID; }
	
	public function getTime() { return $this->time; }
	
	public function getTitle() { return $this->title; }
	
	public function getURL() { return $this->url; }
	
	public function getDescription() { return $this->description; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	# Setters
	public function setSourceID($int) { $this->sourceID = $int; }
	
	public function setTime($int) { $this->time = $int; }
	
	public function setTitle($str) { $this->title = $str;}
	
	public function setURL($str) { $this->url = $str;}
	
	public function setDescription($str) { $this->description = $str;}
}

?>