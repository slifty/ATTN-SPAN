<?php
	$start = strtotime($_GET['s']);
	$end = strtotime($_GET['e']);
	
	if(($start == 0) || ($end == 0) || (!User::isLoggedIn())) {
		global $SITEROOT;
		header("location: http://".$_SERVER['HTTP_HOST'].$SITEROOT."/?p=episode&a=list");
		exit;
	}
	
	// Generate some episodes!
	$day = 60 * 60 * 24;
	for($time = $start ; $time <= $end ; $time += $day) {
		$episode = new Episode();
		$episode->setUserID(User::$currentUser->getUserID());
		$episode->setDateBased($time);
		$episode->save();
		$episode->generate();
	}
	
	header("location: http://".$_SERVER['HTTP_HOST'].$SITEROOT."/?p=episode&a=list");
	exit;
	
?>