<?php
require_once("EpisodeFactory.php");
require_once("UserFactory.php");
require_once("ClipFactory.php");
class Episode {
	
	# Instance Variables
	private $episodeID;
	private $userID;
	private $dateCreated;
	private $title;
	private $thumbnail;
	
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
		$this->title = $dataArray['title'];
		$this->thumbnail = $dataArray['thumbnail'];
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
		
		if($resultArray['episodeCount'] > 0)
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
							       episodes.title = '".DBConn::clean_for_mysql($this->getTitle())."',
							       episodes.thumbnail = '".DBConn::clean_for_mysql($this->getThumbnail())."',
							       episodes.based_date = ".$this->getDateBased()."
							 where episodes.id = ".$this->getEpisodeID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new episode
			$queryString = "INSERT into episodes
							values (0,
									".$this->getUserID().",
									'".DBConn::clean_for_mysql($this->getTitle())."',
									'".DBConn::clean_for_mysql($this->getThumbnail())."',
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
		if($this->getClipCount() > 0)
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
			
			$items = $xmlObj->xpath ('channel/item');
			
			foreach($items as $item) {
				$thumbnail = $item->xpath ('media:thumbnail');
				$content = $item->xpath ('media:group/media:content');
				
				// Generate the context URL
				$parts = explode('/',$item->link);
				$end = array_pop($parts);
				$contextEnd = explode(':', $end);
				$start = array_pop($parts);
				$contextStart = explode(':', $start);
				$base = implode('/',$parts);
				
				// Move the start back ~1 minute
				$contextStart[1] = max(0, (int)$contextStart[1] - 1);
				$contextStart[2] = 0;
				$contextStart = implode(':',$contextStart);
				
				// Move the end forward ~1 minute
				$contextEnd[1] = min(59, (int)$contextEnd[1] + 1);
				$contextEnd[2] = 59;
				$contextEnd = implode(':',$contextEnd);
				
				// Build the context URL
				$context = $base."/".$contextStart."/".$contextEnd;
				
				$clip = new Clip();
				$clip->setEpisodeID($this->getEpisodeID());
				$clip->setSearchID($search->getSearchID());
				$clip->setFeedURL($content['0']['url']);
				$clip->setContextURL($context);
				$clip->setTitle($item->title);
				$clip->setStart($start);
				$clip->setEnd($end);
				$clip->setThumbnail($thumbnail['0']['url']);
				$clip->save();
			}
		}
		
		return;
	}
	
	
	# Getters
	public function getEpisodeID() { return $this->episodeID; }
	
	public function getUserID() { return $this->userID; }
	
	public function getTitle() { return $this->title; }
	
	public function getThumbnail() { return $this->thumbnail; }
	
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
	
	public function getClipCount() {
		// Returns a list of the clips which make up this episode
		
		$mysqli = DBConn::mysqli_connect();
		$queryString = "select count(clips.id) as clipCount
						  from clips
						 where clips.episode_id = ".$this->getEpisodeID();
					
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		
		$resultArray = $result->fetch_assoc();
		
		return (int)$resultArray['clipCount'];
	}
	
	
	# Setters
	public function setUserID($int) { $this->userID = $int;}
	
	public function setTitle($str) { $this->title = $str;}
	
	public function setThumbnail($str) { $this->thumbnail = $str;}
	
	public function setDateBased($timestamp) { $this->dateBased = $timestamp;}
}

?>