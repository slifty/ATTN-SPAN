<?php
set_include_path("/");
require_once("conf.php");
require_once("models/DBConn.php");

// Get connection
$mysqli = DBConn::mysqli_connect();
if(!$mysqli) {
	echo("Could not connect to DB.  Did you follow the instructions in INSTALL?");
	die();
}

// Look up installed version
$result = $mysqli->query("select appinfo.version as version
				  			from appinfo");

if($result->num_rows == 0)
	$version = 0;
else {
	$resultArray = $result->fetch_assoc();
	$version = $resultArray['version'];
	$result->free();
}

echo("Current Version: ".$version."\n");
switch($version) {
	case 0: // Never installed before
		echo("Fresh Install...\n");
		echo("Creating appinfo table\n");
		$mysqli->query("CREATE table appinfo (version varchar(8))") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo values('1');") or print($mysqli->error);
			
	case 1: // First update
		echo("Creating users table\n");
		$mysqli->query("CREATE table users (id int auto_increment primary key,
											username varchar(64),
											password char(32),
											salt char(32),
											join_date datetime)") or print($mysqli->error);
		echo("Creating regions table\n");
		$mysqli->query("CREATE table regions (id int auto_increment primary key,
											name varchar(64),
											code varchar(64),
											parent_id int)") or print($mysqli->error);
		echo("Creating topics table\n");
		$mysqli->query("CREATE table topics (id int auto_increment primary key,
											name varchar(64),
											code varchar(64),
											parent_id int)") or print($mysqli->error);
		echo("Creating searches table\n");
		$mysqli->query("CREATE table searches (id int auto_increment primary key,
											user_id int,
											term varchar(128),
											creation_date datetime)") or print($mysqli->error);
		echo("Creating clips table\n");
		$mysqli->query("CREATE table clips (id int auto_increment primary key,
											episode_id int,
											search_term_id int,
											clip_order int,
											url varchar(255),
											start varchar(16),
											end varchar(16),
											description text,
											title text)") or print($mysqli->error);
		echo("Creating episodes table\n");
		$mysqli->query("CREATE table episodes (id int auto_increment primary key,
											user_id int,
											creation_date datetime)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo values('2');") or print($mysqli->error);
		
	case 2:
		echo("Updating searches table\n");
		$mysqli->query("ALTER table searches
						  drop column user_id,
						   add column meta_id int AFTER term,
						   add column meta_type ENUM('r','t') AFTER meta_id");
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo values('3');") or print($mysqli->error);
	
	case 3: 
		echo("Creating users_meta table\n");
		$mysqli->query("CREATE table users_meta (id int auto_increment primary key,
											user_id int,
											meta_id int,
											meta_type ENUM('r','t'))");
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo values('4');") or print($mysqli->error);
		
	case 4: 
		echo("Removing regions table\n");
		$mysqli->query("DROP table regions");
		echo("Removing topics table\n");
		$mysqli->query("DROP table topics");
		echo("Removing users_meta table\n");
		$mysqli->query("DROP table users_meta");
		echo("Creating interests table\n");
		$mysqli->query("CREATE table interests (id int auto_increment primary key,
											name varchar(64),
											code varchar(64),
											type enum('r','t'),
											parent_id int)") or print($mysqli->error);
		echo("Updating searches table\n");
		$mysqli->query("ALTER table searches
						  drop column meta_id,
						  drop column meta_type,
						   add column interest_id int AFTER term");
		echo("Creating users_interests table\n");
		$mysqli->query("CREATE table users_interests (id int auto_increment primary key,
											user_id int,
											interest_id int)");
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo values('5');") or print($mysqli->error);
	
	case 5: 
		echo("Updating episodes table\n");
		$mysqli->query("ALTER table episodes
						   add column based_date datetime AFTER user_id");
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo values('6');") or print($mysqli->error);
		
	case 6: 
		echo("Updating clips table\n");
		$mysqli->query("ALTER table clips
						  drop column url,
						   add column feed_url varchar(255) AFTER clip_order,
						   add column context_url varchar(255) AFTER clip_order");
		echo("Updating app version\n");
		$mysqli->query("DELETE from appinfo") or print($mysqli->error);
		$mysqli->query("INSERT into appinfo values('7');") or print($mysqli->error);
	
	default:
		echo("Finished updating the schema\n");
}
?>