<?php
require_once("UserFactory.php");
require_once("InterestFactory.php");
require_once("EpisodeFactory.php");
class User {
	public static $currentUser; // The user object for the current user
	
	# Instance Variables
	private $userID;
	private $username;
	private $password;
	private $passwordConfirm;
	private $currentPassword;
	private $dateJoined;
	
	private $addedInterests = array();
	private $removedInterests = array();
	
	# Data Methods
	public function __construct() {
		$this->userID = 0;
		$this->username = "";
		$this->password = "";
		$this->passwordConfirm = "";
		$this->currentPassword = "";
		$this->dateJoined = 0;
	}
	
	public function load($dataArray) {
		$this->userID = $dataArray['userID'];
		$this->username = $dataArray['username'];
		$this->dateJoined = $dataArray['dateJoined'];
	}
	
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;

		$mysqli = DBConn::mysqli_connect();
		if($this->getUserID() != 0) {
			// Update an existing user
			$queryString = "UPDATE users
							   set users.username = '".DBConn::clean_for_mysql($this->getUsername())."',
								   users.password = ".($this->getPassword() == ""?"users.password":"md5(concat('".md5(DBConn::clean_for_mysql($this->getPassword()))."', users.salt))")."
							 where users.id = ".$this->getUserID();
						
			$mysqli->query($queryString)
				or print($mysqli->error);
		}
		else {
			// Create a new user
			$salt = "";
			for($i = 0; $i < 32; $i++) $salt .= mt_rand(0,9);
			$salt = md5($salt);
			$password = md5(md5($this->getPassword()).$salt);
			$queryString = "INSERT into users
							values (0,
									'".DBConn::clean_for_mysql($this->getUsername())."',
									'".DBConn::clean_for_mysql($password)."',
									'".DBConn::clean_for_mysql($salt)."',
									NOW())";
			
			$mysqli->query($queryString)
				or print($mysqli->error);
			
			$this->userID = $mysqli->insert_id;
		}
		
		// Add new interests
		foreach($this->addedInterests as $interest) {
			$queryString = "INSERT into users_interests
								 values (0,
										 ".$this->getUserID().",
										 ".$interest->getInterestID().")";
			$mysqli->query($queryString);
		}

		// Remove new interests
		foreach($this->removedInterests as $interest) {
			$queryString = "DELETE from users_interests
								  where users_interests.user_id = ".$this->getUserID()."
								    and users_interests.interest_id = ".$interest->getInterestID();
			$mysqli->query($queryString);
		}
	}
	
	
	# Getters
	public function getUserID() { return $this->userID; }
	
	public function getUsername() { return $this->username; }
	
	public function getPassword() { return $this->password; }
	
	public function getDateJoined() { return $this->dateJoined; }
	
	public function getInterests() {
		// Return all the interests that this user has flagged
		$mysqli = DBConn::mysqli_connect();
		$queryString = "SELECT users_interests.interest_id as interestID
						  FROM users_interests
						 WHERE users_interests.user_id = ".$this->getUserID();
		
		$result = $mysqli->query($queryString);
		$interestIDs = array();
		while($resultArray = $result->fetch_assoc())
			$interestIDs[] = $resultArray['interestID'];
		
		return InterestFactory::getObjects($interestIDs);
	}
	
	public function getEpisodes() {
		// Return all the episodes for this user
		$mysqli = DBConn::mysqli_connect();
		$queryString = "SELECT episodes.id as episodeID
						  FROM episodes
						 WHERE episodes.user_id = ".$this->getUserID();
		
		$result = $mysqli->query($queryString);
		$episodeIDs = array();
		while($resultArray = $result->fetch_assoc())
			$episodeIDs[] = $resultArray['episodeID'];
		
		return EpisodeFactory::getObjects($episodeIDs);
	}
	
	
	# Setters
	public function setUsername($str) { $this->username = $str; }
	
	public function setPassword($str) { $this->password = $str; }
	
	public function setPasswordConfirm($str) { $this->passwordConfirm = $str;}
	
	public function setCurrentPassword($str) { $this->currentPassword = $str;}
	
	
	# Interest methods
	public function addInterest($interest) {
		$this->addedInterests[] = $interest;
	}
	
	public function removeInterest($interests) {
		$this->removedInterests[] = $interest;
	}
	
	
	# Static Methods
	public static function isLoggedIn() {
		return User::$currentUser->getUserID() != 0;
	}
	
	public static function login($username, $password) {
		$mysqli = DBConn::mysqli_connect();
		$username = DBConn::clean_for_mysql($username);
		$password = DBConn::clean_for_mysql($password);
		
		// Check authentication information
		$queryString = "select users.id as userID
						   from users
						  where users.username = '".$username."'
						    and users.password = md5(concat('".$password."',users.salt)) limit 0,1";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
			
		if($result->num_rows != 0) {
			// Login successful
			$resultArray = $result->fetch_assoc();
			User::$currentUser = UserFactory::getObject($resultArray['userID']);
			setcookie("username", $username, time()+1728000);
 			setcookie("password", $password, time()+1728000);
			$result->free();
			return true;
		}
		else {
			User::logout();
			return false;
		}
	}
	
	public function logout() {
		setcookie("username", "", time()+1728000); 
		setcookie("password", "", time()+1728000);
		User::$currentUser = new User();
	}
	
	public static function getCookieDomain() {
		$domain = $_SERVER['HTTP_HOST'];
		if(strtolower(substr($domain,0,4)) == 'www.') $domain = substr($domain,4);
		if(substr($domain,0,1) != '.') $domain = '.'.$domain;
		if(strpos($domain, ":"))
			return substr($domain, 0, strpos($domain,":"));

		return $domain;
	}
}

?>