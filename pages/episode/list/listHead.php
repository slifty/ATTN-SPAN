<?php
	if(!User::isLoggedIn()) {
		global $SITEROOT;
		header("location: http://".$_SERVER['HTTP_HOST'].$SITEROOT);
		exit;
	}
	
?>