<?php
require_once("ClipFactory.php");
require_once("FlagFactory.php");
class Clip {
	
	# Instance Variables
	private $clipID;
	private $episodeID;
	private $searchID;
	private $sourceID;
	private $clipOrder;
	private $feedURL;
	private $contextURL;
	private $start;
	private $end;
	private $description;
	private $title;
	private $thumbnail;
	
	# Data Methods
	public function __construct() {
		$this->clipID = 0;
		$this->episodeID = 0;
		$this->searchID = 0;
		$this->sourceID = 0;
		$this->clipOrder = 0;
		$this->feedURL = "";
		$this->contextURL = "";
		$this->start = 0;
		$this->end = 0;
		$this->description = "";
		$this->title = "";
		$this->thumbnail = "";
	}
	
	public function load($dataArray) {
		$this->clipID = $dataArray['clipID'];
		$this->episodeID = $dataArray['episodeID'];
		$this->searchID = $dataArray['searchID'];
		$this->sourceID = $dataArray['sourceID'];
		$this->clipOrder = $dataArray['clipOrder'];
		$this->feedURL = $dataArray['feedURL'];
		$this->contextURL = $dataArray['contextURL'];
		$this->start = $dataArray['start'];
		$this->end = $dataArray['end'];
		$this->description = $dataArray['description'];
		$this->title = $dataArray['title'];
		$this->thumbnail = $dataArray['thumbnail'];
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
							       clips.source_id = ".$this->getSourceID().",
							       clips.clip_order = ".$this->getClipOrder().",
								   clips.contextURL = '".DBConn::clean_for_mysql($this->getContextURL())."',
								   clips.feedURL = '".DBConn::clean_for_mysql($this->getFeedURL())."',
								   clips.start = ".DBConn::clean_for_mysql($this->getStart()).",
								   clips.end = ".DBConn::clean_for_mysql($this->getEnd()).",
								   clips.description = '".DBConn::clean_for_mysql($this->getDescription())."',
								   clips.title = '".DBConn::clean_for_mysql($this->getTitle())."',
								   clips.thumbnail = '".DBConn::clean_for_mysql($this->getThumbnail())."'
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
									".$this->getSourceID().",
									".$this->getClipOrder().",
									'".DBConn::clean_for_mysql($this->getContextURL())."',
									'".DBConn::clean_for_mysql($this->getFeedURL())."',
									".DBConn::clean_for_mysql($this->getStart()).",
									".DBConn::clean_for_mysql($this->getEnd()).",
									'".DBConn::clean_for_mysql($this->getDescription())."',
									'".DBConn::clean_for_mysql($this->getTitle())."',
									'".DBConn::clean_for_mysql($this->getThumbnail())."')";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->clipID = $mysqli->insert_id;
		}
	}

	
	# Getters
	public function getClipID() { return $this->clipID; }

	public function getEpisodeID() { return $this->episodeID; }

	public function getSearchID() { return $this->searchID; }
	
	public function getSourceID() { return $this->sourceID; }
	
	public function getFeedURL() { return $this->feedURL; }
	
	public function getContextURL() { return $this->contextURL; }
	
	public function getClipOrder() { return $this->clipOrder; }
	
	public function getStart() { return $this->start; }
	
	public function getEnd() { return $this->end; }
	
	public function getDescription() { return $this->description; }
	
	public function getTitle() { return $this->title; }
	
	public function getThumbnail() { return $this->thumbnail; }
	
	public function getFlags() {
		$mysqli = DBConn::mysqli_connect();
		$queryString = "select flags.id as flagID
						  from flags
						 where flags.source_id = ".$this->getSourceID()."
						   and flags.time >= ".$this->getStart()."
						   and flags.time <= ".$this->getEnd();
					
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		$flagIDs = array();
		while($resultArray = $result->fetch_assoc())
			$flagIDs[] = $resultArray['flagID'];
		
		return FlagFactory::getObjects($flagIDs);
	}
	
	
	# Setters
	public function setEpisodeID($int) { $this->episodeID = $int;}
	
	public function setSearchID($int) { $this->searchID = $int;}
	
	public function setSourceID($int) { $this->sourceID = $int;}

	public function setFeedURL($str) { $this->feedURL = $str;}
	
	public function setContextURL($str) { $this->contextURL = $str;}
	
	public function setStart($int) { $this->start = $int;}
	
	public function setClipOrder($int) { $this->clipOrder = $int;}
	
	public function setEnd($int) { $this->end = $int;}
	
	public function setDescription($str) { $this->description = $str;}
	
	public function setTitle($str) { $this->title = $str;}
	
	public function setThumbnail($str) { $this->thumbnail = $str;}
}

?>