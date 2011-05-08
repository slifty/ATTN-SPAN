<?php
require_once("EpisodeFactory.php");
require_once("UserFactory.php");
require_once("ClipFactory.php");
class Episode {
	
	# Instance Variables
	private $episodeID;
	private $userID;
	private $dateCreated;
	
	# Data Methods
	public function __construct() {
		$this->episodeID = 0;
		$this->userID = 0;
		$this->dateBased = 0;
		$this->dateCreated = 0;
	}
	
	public function load($dataArray) {
		$this->episodeID = $dataArray['episodeID'];
		$this->userID = $dataArray['userID'];
		$this->dateBased = $dataArray['dateBased'];
		$this->dateCreated = $dataArray['dateCreated'];
	}
	
	public function validate() {
		$mysqli = DBConn::mysqli_connect();
		
		// Make sure this user doesn't already have an episode for this date
		$queryString = "select count(*) as episodeCount
						  from episodes
						 where episodes.user_id = ".$this->getUserID()."
						   and episodes.based_date = ".$this->getDateBased()."
						   and episodes.id != ".$this->getEpisodeID();
		$result = $mysqli->query($queryString);
		$resultArray = $result->fetch_assoc();
		
		if($resultArray['episodeCount'] > 0);
			return false;
		
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;
		
		$mysqli = DBConn::mysqli_connect();
		if($this->getEpisodeID() != 0) {
			// Update an existing episode
			$queryString = "UPDATE episodes
							   set episodes.user_id = ".$this->getUserID().",
							       episodes.based_date = ".$this->getDateBased()."
							 where episodes.id = ".$this->getEpisodeID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new interest
			$queryString = "INSERT into episodes
							values (0,
									".$this->getUserID().",
									'".date('Y-m-d H:i:s',$this->getDateBased())."',
									NOW())";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->episodeID = $mysqli->insert_id;
		}
	}
	
	public function generate() {
		// Make sure this is a saved episode
		if($this->getEpisodeID() == 0)
			return;
			
		// Make sure this episode doesn't already have clips
		if(sizeof($this->getClips()) > 0)
			return;
		
		// Generates a list of the episode clips
		$user = UserFactory::getObject($this->getUserID());
		$interests = $user->getInterests();
		$searches = array();
		
		foreach($interests as $interest)
			$searches = array_merge($searches, $interest->getSearches());
		
		foreach($searches as $search) {
			$requests = array();
			$requests[] = array('t'=>'date_range',
							 	'vs'=>date('m/d/Y',$this->getDateBased()),
							 	've'=>date('m/d/Y',$this->getDateBased()),
							 	'a'=>'and');
			$requests[] = array('t'=>'match',
							 	'v'=>$search->getTerm(),
							 	'a'=>'and');
			
			$query = http_build_query(array('f'=>$requests));
			$url = "http://metavid.ucsc.edu/w/index.php?title=Special:MvExportSearch&order=recent&tracks=ht_en%2Canno_en%2Cthomas_en&".$query;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$xmlStr = curl_exec($ch);
			curl_close($ch); 
			
			$xmlObj = new SimpleXMLElement($xmlStr);
			
						
			echo("</br >");
			echo("</br >");
			echo("</br >");
			echo("</br >");
			print_r($xmlObj);


			$items = $xmlObj->channel->item;
			foreach($items as $item) {
				$title = $item->title;
				
				$clip = new Clip();
				$clip->setEpisodeID($this->getEpisodeID());
				$clip->setSearchID($search->getSearchID());
				$clip->setFeedURL($item->enclosure['url']);
				$clip->setContextURL($item->link);
				$clip->setTitle($item->title);
				$clip->save();
			}
		}
		
		return;
	}
	
	
	# Getters
	public function getEpisodeID() { return $this->episodeID; }
	
	public function getUserID() { return $this->userID; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	public function getDateBased() { return $this->dateBased; }
	
	public function getClips() {
		// Returns a list of the clips which make up this episode
		
		$mysqli = DBConn::mysqli_connect();
		$queryString = "select clips.id as clipID
						  from clips
						 where clips.episode_id = ".$this->getEpisodeID()."
					  order by clips.clip_order";
					
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		$clipIDs = array();
		while($resultArray = $result->fetch_assoc())
			$clipIDs[] = $resultArray['clipID'];
		
		return ClipFactory::getObjects($clipIDs);
	}
	
	
	# Setters
	public function setUserID($int) { $this->userID = $int;}
	
	public function setDateBased($timestamp) { $this->dateBased = $timestamp;}
}

?>