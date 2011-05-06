<?php
require_once("ClipFactory.php");
class Clip {
	
	# Instance Variables
	private $clipID;
	private $episodeID;
	private $searchID;
	private $order;
	private $feedURL;
	private $contextURL;
	private $start;
	private $end;
	private $description;
	private $title;
	
	# Data Methods
	public function __construct() {
		$this->clipID = 0;
		$this->episodeID = 0;
		$this->searchID = 0;
		$this->order = 0;
		$this->feedURL = "";
		$this->contextURL = "";
		$this->start = "";
		$this->end = "";
		$this->description = "";
		$this->title = "";
	}
	
	public function load($dataArray) {
		$this->clipID = $dataArray['clipID'];
		$this->episodeID = $dataArray['episodeID'];
		$this->searchID = $dataArray['searchID'];
		$this->order = $dataArray['order'];
		$this->feedURL = $dataArray['feedURL'];
		$this->contextURL = $dataArray['contextURL'];
		$this->start = $dataArray['start'];
		$this->end = $dataArray['end'];
		$this->description = $dataArray['description'];
		$this->title = $dataArray['title'];
	}
	
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;
		
		$mysqli = DBConn::mysqli_connect();
		if($this->getClipID() != 0) {
			// Update an existing clip
			$queryString = "UPDATE clips
							   set clips.episode_id = ".$this->getEpisodeID().",
							       clips.search_term_id = ".$this->getSearchID().",
							       clips.order = ".$this->getOrder().",
								   clips.contextURL = '".DBConn::clean_for_mysql($this->getContextURL())."',
								   clips.feedURL = '".DBConn::clean_for_mysql($this->getFeedURL())."',
								   clips.start = '".DBConn::clean_for_mysql($this->getStart())."',
								   clips.end = '".DBConn::clean_for_mysql($this->getEnd())."',
								   clips.description = '".DBConn::clean_for_mysql($this->getDescription())."',
								   clips.title = '".DBConn::clean_for_mysql($this->getTitle())."'
							 where clips.id = ".$this->getClipID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new interest
			$queryString = "INSERT into clips
							values (0,
									".$this->getEpisodeID().",
									".$this->getSearchID().",
									".$this->getOrder().",
									'".DBConn::clean_for_mysql($this->getContextURL())."',
									'".DBConn::clean_for_mysql($this->getFeedURL())."',
									'".DBConn::clean_for_mysql($this->getStart())."',
									'".DBConn::clean_for_mysql($this->getEnd())."',
									'".DBConn::clean_for_mysql($this->getDescription())."',
									'".DBConn::clean_for_mysql($this->getTitle())."')";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->clipID = $mysqli->insert_id;
		}
	}

	
	# Getters
	public function getClipID() { return $this->clipID; }

	public function getEpisodeID() { return $this->episodeID; }

	public function getSearchID() { return $this->searchID; }
	
	public function getFeedURL() { return $this->feedURL; }
	
	public function getContextURL() { return $this->contextURL; }
	
	public function getOrder() { return $this->order; }
	
	public function getStart() { return $this->start; }
	
	public function getEnd() { return $this->end; }
	
	public function getDescription() { return $this->description; }
	
	public function getTitle() { return $this->title; }
		
	
	# Setters
	public function setEpisodeID($int) { $this->episodeID = $int;}
	
	public function setSearchID($int) { $this->searchID = $int;}

	public function setFeedURL($str) { $this->feedURL = $str;}
	
	public function setContextURL($str) { $this->contextURL = $str;}
	
	public function setStart($str) { $this->start = $str;}
	
	public function setOrder($int) { $this->order = $int;}
	
	public function setEnd($str) { $this->end = $str;}
	
	public function setDescription($str) { $this->description = $str;}
	
	public function setTitle($str) { $this->title = $str;}
}

?>