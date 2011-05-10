<?php
	set_include_path("../");
	include_once("head.php");
	
	if(!User::isLoggedIn())
		exit;
	
	$clip = ClipFactory::getObject(isset($_POST['clipID'])?$_POST['clipID']:0);
	if($clip->getClipID() == 0)
		exit;
	
	$flag = new Flag();
	$flag->setUserID(User::$currentUser->getUserID());
	$flag->setSourceID($clip->getSourceID());
	$flag->setTime($clip->getStart() + (isset($_POST['time'])?$_POST['time']:0));
	$flag->setType((isset($_POST['type'])?$_POST['type']:""));
	$flag->save();
?>