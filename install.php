<?php
set_include_path("/");
require_once("conf.php");
require_once("models/DBConn.php");

// Get connection
$mysqli = DBConn::mysqli_connect();
if($mysqli) {
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
		$mysqli->query("create table appinfo (version varchar(8))") or print($mysqli->error);
		$mysqli->query("insert into appinfo values('1');") or print($mysqli->error);
			
	case 1: // First update
		echo("Creating users table\n");
		$mysqli->query("create table users (id int auto_increment primary key,
											username varchar(64),
											password char(32),
											salt char(32),
											join_date datetime)") or print($mysqli->error);
		echo("Creating regions table\n");
		$mysqli->query("create table regions (id int auto_increment primary key,
											name varchar(64),
											code varchar(64),
											parent_id int)") or print($mysqli->error);
		echo("Creating topics table\n");
		$mysqli->query("create table topics (id int auto_increment primary key,
											name varchar(64),
											code varchar(64),
											parent_id int)") or print($mysqli->error);
		echo("Creating searches table\n");
		$mysqli->query("create table searches (id int auto_increment primary key,
											user_id int,
											term varchar(128),
											creation_date datetime)") or print($mysqli->error);
		echo("Creating clips table\n");
		$mysqli->query("create table clips (id int auto_increment primary key,
											episode_id int,
											search_term_id int,
											clip_order int,
											url varchar(255),
											start varchar(16),
											end varchar(16),
											description text,
											title text)") or print($mysqli->error);
		echo("Creating episodes table\n");
		$mysqli->query("create table episodes (id int auto_increment primary key,
											user_id int,
											creation_date datetime)") or print($mysqli->error);
		
		echo("Updating app version\n");
		$mysqli->query("delete from appinfo") or print($mysqli->error);
		$mysqli->query("insert into appinfo values('2');") or print($mysqli->error);
	default:
		echo("Finished updating the schema\n");
}
?>