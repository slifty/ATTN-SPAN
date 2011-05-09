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

// FLORIDA                         FL
// GEORGIA                         GA
// GUAM                            GU
// HAWAII                          HI
// IDAHO                           ID
// ILLINOIS                        IL
// INDIANA                         IN
// IOWA                            IA
// KANSAS                          KS
// KENTUCKY                        KY
// LOUISIANA                       LA
// MAINE                           ME
// MARSHALL ISLANDS                MH
// MARYLAND                        MD
// MASSACHUSETTS                   MA
// MICHIGAN                        MI
// MINNESOTA                       MN
// MISSISSIPPI                     MS
// MISSOURI                        MO
// MONTANA                         MT
// NEBRASKA                        NE
// NEVADA                          NV
// NEW HAMPSHIRE                   NH
// NEW JERSEY                      NJ
// NEW MEXICO                      NM
// NEW YORK                        NY
// NORTH CAROLINA                  NC
// NORTH DAKOTA                    ND
// NORTHERN MARIANA ISLANDS        MP
// OHIO                            OH
// OKLAHOMA                        OK
// OREGON                          OR
// PALAU                           PW
// PENNSYLVANIA                    PA
// PUERTO RICO                     PR
// RHODE ISLAND                    RI
// SOUTH CAROLINA                  SC
// SOUTH DAKOTA                    SD
// TENNESSEE                       TN
// TEXAS                           TX
// UTAH                            UT
// VERMONT                         VT
// VIRGIN ISLANDS                  VI
// VIRGINIA                        VA
// WASHINGTON                      WA
// WEST VIRGINIA                   WV
// WISCONSIN                       WI
// WYOMING                         WY


?>