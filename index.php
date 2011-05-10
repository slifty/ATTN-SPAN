<?php include_once("head.php"); ?>
<?php
	$file = "pages/".$p."/".$a."/".$a."Head.php";
	if(file_exists($file)) { include($file); }
?>
<html>
	<head>
		<title>ATTN-SPAN: Because nobody cares THAT much about congress</title>
		
		<link rel="stylesheet" href="styles/jquery-ui-1.8.12.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
		
		<?php
			$file = "pages/".$p."/".$a."/".$a.".css";
			if(file_exists($file)) { ?><link rel="stylesheet" href="<?php echo($file); ?>" type="text/css" media="screen" title="no title" charset="utf-8"> <?php }
		?>
		
		<script src="scripts/jquery-1.5.2.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="scripts/jquery-ui-1.8.12.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="scripts/jquery.animate-shadow-min.js" type="text/javascript" charset="utf-8"></script>
		
		<?php
			$file = "pages/".$p."/".$a."/".$a.".js";
			if(file_exists($file)) { ?><script src="<?php echo($file); ?>" type="text/javascript" charset="utf-8"></script> <?php }
		?>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div id="logo"><h1>ATTN-SPAN</h1></div>
				<div id="login">
					<?php
						if(User::isLoggedIn()) {
							?>
							<form method="POST">
								<input type="hidden" name="logout" value=1 />
								<input type="submit" value="Log Out" />
							</form>
							<?php
						} else {
							?>
							<form method="POST">
								<ul>
									<li><label for="username">Username:</label><input type="text" name="username" value="" /></li>
									<li><label for="password">Password:</label><input type="password" name="password" value="" /></li>
									<li><label>&nbsp;</label><input type="submit" name="login" value="Log In" /></li>
								</ul>
							</form>
							<?php
						}
					?>
				</div>
			</div>
			<div id="main">
				<?php
					$file = "pages/".$p."/".$a."/".$a.".php";
					if(file_exists($file))
						include($file);
					else
						echo("404!");
				?>
			</div>
			<div id="footer"></div>
		</div>
	</body>
</html>