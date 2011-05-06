<?php

set_include_path("/");
require_once("conf.php");
require_once("models/DBConn.php");
require_once("models/Interest.php");
require_once("models/User.php");
require_once("models/Search.php");
require_once("models/Episode.php");

$mysqli = DBConn::mysqli_connect();
$mysqli->query("delete from users");
$mysqli->query("delete from interests");
$mysqli->query("delete from episodes");
$mysqli->query("delete from clips");
$mysqli->query("delete from users_interests");

$tempUser = new User();
$tempUser->setUsername("temp");
$tempUser->setPassword("password1");
$tempUser->save();
User::login("temp",md5("password1"));

$regionUSA = new Interest();
$regionUSA->setName("United States of America");
$regionUSA->setCode("USA");
$regionUSA->setType("r");
$regionUSA->save();

$regionMA = new Interest();
$regionMA->setName("Massachusetts");
$regionMA->setCode("USA/MA");
$regionMA->setType("r");
$regionMA->setParentID($regionUSA->getInterestID());
$regionMA->save();

$tempUser->addInterest($regionMA);
$tempUser->save();

$searchTerm = new Search();
$searchTerm->setInterestID($regionMA->getInterestID());
$searchTerm->setTerm("Obama");
$searchTerm->save();

$episode = new Episode();
$episode->setUserID($tempUser->getUserID());
$episode->setDateBased(strtotime("5 January 2011"));
$episode->save();
$episode->generate();

?>