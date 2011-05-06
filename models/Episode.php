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
			$items = $xmlObj->channel->item;
			foreach($items as $item) {
				$title = $item->title;
				
				$clip = new Clip();
				$clip->setEpisodeID($this->getEpisodeID());
				$clip->setSearchID($search->getSearchID());
				$clip->setFeedURL($item->enclosure['url']);
				$clip->setContextURL($item->link);
				$clip->setTitle($item->title);
				$clip->setDescription($item->description);
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
		return array();
	}
	
	
	# Setters
	public function setUserID($int) { $this->userID = $int;}
	
	public function setDateBased($timestamp) { $this->dateBased = $timestamp;}
}

?>