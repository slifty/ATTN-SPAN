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
$mysqli->query("delete from sources");

$regionUSA = new Interest();
$regionUSA->setName("United States of America");
$regionUSA->setCode("USA");
$regionUSA->setType("r");
$regionUSA->save();

$region = new Interest();
$region->setName("Alabama");
$region->setCode("USA/AL");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Alabama");
$searchTerm->save();

$region = new Interest();
$region->setName("Alaska");
$region->setCode("USA/AK");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Alaska");
$searchTerm->save();

$region = new Interest();
$region->setName("Arizona");
$region->setCode("USA/AZ");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Arizona");
$searchTerm->save();

$region = new Interest();
$region->setName("Arkansas");
$region->setCode("USA/AR");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Arkansas");
$searchTerm->save();

$region = new Interest();
$region->setName("California");
$region->setCode("USA/CA");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("California");
$searchTerm->save();

$region = new Interest();
$region->setName("Colorado");
$region->setCode("USA/CO");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Colorado");
$searchTerm->save();

$region = new Interest();
$region->setName("Connecticut");
$region->setCode("USA/CT");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Connecticut");
$searchTerm->save();

$region = new Interest();
$region->setName("Delaware");
$region->setCode("USA/DE");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Delaware");
$searchTerm->save();

$region = new Interest();
$region->setName("Florida");
$region->setCode("USA/FL");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Florida");
$searchTerm->save();

$region = new Interest();
$region->setName("Georgia");
$region->setCode("USA/GA");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Georgia");
$searchTerm->save();

$region = new Interest();
$region->setName("Hawaii");
$region->setCode("USA/HI");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Hawaii");
$searchTerm->save();

$region = new Interest();
$region->setName("Idaho");
$region->setCode("USA/ID");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Idaho");
$searchTerm->save();

$region = new Interest();
$region->setName("Illinois");
$region->setCode("USA/IL");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Illinois");
$searchTerm->save();

$region = new Interest();
$region->setName("Indiana");
$region->setCode("USA/IN");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Indiana");
$searchTerm->save();

$region = new Interest();
$region->setName("Iowa");
$region->setCode("USA/IA");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Iowa");
$searchTerm->save();

$region = new Interest();
$region->setName("Kansas");
$region->setCode("USA/KS");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Kansas");
$searchTerm->save();

$region = new Interest();
$region->setName("Kentucky");
$region->setCode("USA/KY");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Kentucky");
$searchTerm->save();

$region = new Interest();
$region->setName("Louisiana");
$region->setCode("USA/LA");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Louisiana");
$searchTerm->save();

$region = new Interest();
$region->setName("Maine");
$region->setCode("USA/ME");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Maine");
$searchTerm->save();

$region = new Interest();
$region->setName("Maryland");
$region->setCode("USA/MD");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Maryland");
$searchTerm->save();

$region = new Interest();
$region->setName("Massachusetts");
$region->setCode("USA/MA");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Massachusetts");
$searchTerm->save();

$region = new Interest();
$region->setName("Michigan");
$region->setCode("USA/MI");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Michigan");
$searchTerm->save();

$region = new Interest();
$region->setName("Minnesota");
$region->setCode("USA/MN");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Minnesota");
$searchTerm->save();

$region = new Interest();
$region->setName("Mississippi");
$region->setCode("USA/MS");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Mississippi");
$searchTerm->save();

$region = new Interest();
$region->setName("Missouri");
$region->setCode("USA/MO");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Missouri");
$searchTerm->save();

$region = new Interest();
$region->setName("Montana");
$region->setCode("USA/MT");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Montana");
$searchTerm->save();

$region = new Interest();
$region->setName("Nebraska");
$region->setCode("USA/NE");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Nebraska");
$searchTerm->save();

$region = new Interest();
$region->setName("Nevada");
$region->setCode("USA/NV");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Nevada");
$searchTerm->save();

$region = new Interest();
$region->setName("New Hampshire");
$region->setCode("USA/NH");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("New Hampshire");
$searchTerm->save();

$region = new Interest();
$region->setName("New Jersey");
$region->setCode("USA/NJ");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("New Jersey");
$searchTerm->save();

$region = new Interest();
$region->setName("New Mexico");
$region->setCode("USA/NM");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("New Mexico");
$searchTerm->save();

$region = new Interest();
$region->setName("New York");
$region->setCode("USA/NY");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("New York");
$searchTerm->save();

$region = new Interest();
$region->setName("North Carolina");
$region->setCode("USA/NC");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("North Carolina");
$searchTerm->save();

$region = new Interest();
$region->setName("North Dakota");
$region->setCode("USA/ND");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("North Dakota");
$searchTerm->save();

$region = new Interest();
$region->setName("Ohio");
$region->setCode("USA/Oh");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Ohio");
$searchTerm->save();

$region = new Interest();
$region->setName("Oklahoma");
$region->setCode("USA/OK");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Oklahoma");
$searchTerm->save();

$region = new Interest();
$region->setName("Oregon");
$region->setCode("USA/OR");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Oregon");
$searchTerm->save();

$region = new Interest();
$region->setName("Pennsylvania");
$region->setCode("USA/PA");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Pennsylvania");
$searchTerm->save();

$region = new Interest();
$region->setName("Rhode Island");
$region->setCode("USA/RI");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Rhode Island");
$searchTerm->save();

$region = new Interest();
$region->setName("South Carolina");
$region->setCode("USA/SC");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("South Carolina");
$searchTerm->save();

$region = new Interest();
$region->setName("South Dakota");
$region->setCode("USA/SD");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("South Dakota");
$searchTerm->save();

$region = new Interest();
$region->setName("Tennessee");
$region->setCode("USA/TN");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Tennessee");
$searchTerm->save();

$region = new Interest();
$region->setName("Texas");
$region->setCode("USA/TX");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Texas");
$searchTerm->save();

$region = new Interest();
$region->setName("Utah");
$region->setCode("USA/UT");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Utah");
$searchTerm->save();

$region = new Interest();
$region->setName("Vermont");
$region->setCode("USA/VT");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Vermont");
$searchTerm->save();

$region = new Interest();
$region->setName("Virginia");
$region->setCode("USA/VA");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Virginia");
$searchTerm->save();

$region = new Interest();
$region->setName("Washington");
$region->setCode("USA/WA");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Washington");
$searchTerm->save();

$region = new Interest();
$region->setName("West Virginia");
$region->setCode("USA/WV");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("West Virginia");
$searchTerm->save();

$region = new Interest();
$region->setName("Wisconsin");
$region->setCode("USA/WI");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Wisconsin");
$searchTerm->save();

$region = new Interest();
$region->setName("Wyoming");
$region->setCode("USA/WY");
$region->setType(Interest::REGION);
$region->setParentID($regionUSA->getInterestID());
$region->save();

$searchTerm = new Search();
$searchTerm->setInterestID($region->getInterestID());
$searchTerm->setTerm("Wyoming");
$searchTerm->save();

?>