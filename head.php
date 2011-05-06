<?php
	// Setup the basics
	session_start();
	set_include_path("/");
	require_once("conf.php");
	
	// Load in the models
	require_once("models/DBConn.php");
	require_once("models/Clip.php");
	require_once("models/Episode.php");
	require_once("models/Search.php");
	require_once("models/Interest.php");
	require_once("models/User.php");
	
	// Log in
	if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
		User::login($_COOKIE['username'], $_COOKIE['password']);
	if(isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password']))
		User::login($_POST['username'], md5($_POST['password']));	
	if(isset($_POST['logout']))
		User::logout();
		
	// Crappy routing
	$p = isset($_GET['p'])?$_GET['p']:"site";
	$a = isset($_GET['a'])?$_GET['a']:"home";
	
	$p = preg_replace('/[^a-zA-Z0-9]/','',$p);
	$a = preg_replace('/[^a-zA-Z0-9]/','',$a);
?>