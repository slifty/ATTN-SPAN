<?php
	if(User::isLoggedIn()) {
		global $SITEROOT;
		header("location: http://".$_SERVER['HTTP_HOST'].$SITEROOT);
		exit;
	}
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		// Try to create a new user
		$newUser = new User();
		$newUser->setUsername($_POST['username']);
		$newUser->setPassword($_POST['password']);
		$newUser->setPasswordConfirm($_POST['passwordConfirm']);
		
		foreach($_POST['regions'] as $interestID) {
			$interest = InterestFactory::getObject($interestID);
			$newUser->addInterest($interest);
		}
		
		$newUser->save();
		User::login($newUser->getUsername(), md5($newUser->getPassword()));
		
		if(User::isLoggedIn()) {
			global $SITEROOT;
			header("location: http://".$_SERVER['HTTP_HOST'].$SITEROOT);
			exit;
		}
	}
?>