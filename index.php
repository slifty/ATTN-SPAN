<?php include_once("head.php"); ?>
<html>
	<head>
		<title>ATTN-SPAN: Because nobody cares THAT much about congress</title>
		
		<link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<?PHP
			$file = "pages/".$p."/".$a.".css";
			if(file_exists($file)) { ?><script src="<?php echo($file); ?>" type="text/javascript" charset="utf-8"></script> <?php }
		?>
		
		<script src="scripts/jquery-1.5.2.min.js" type="text/javascript" charset="utf-8"></script>
		<?PHP
			$file = "pages/".$p."/".$a.".js";
			if(file_exists($file)) { ?><script src="<?php echo($file); ?>" type="text/javascript" charset="utf-8"></script> <?php }
		?>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div id="login"></div>
			</div>
			<div id="main">
				<?php
					$file = "pages/".$p."/".$a.".php";
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