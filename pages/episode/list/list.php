<?php

	$episodes = User::$currentUser->getEpisodes();
	
	if(sizeof($episodes) == 0) {
		$start = time() - 60*60*24*30; // Generate 30 episodes
		$end = time();
		?>
		Looks like you don't have any episodes yet!
		<a href="?p=episode&a=generate&s=<?php echo(urlencode(date('m/d/Y',$start)))?>&e=<?php echo(urlencode(date('m/d/Y',$end)))?>">Generate some.</a>
		<?php
	} else {
		foreach($episodes as $episode) {
			?>
			<div class='episode'><a href="?p=episode&a=view&e=<?php echo($episode->getEpisodeID());?>">View Episode <?php echo($episode->getDateBased());?></a></div>
			<?php
		}
	}
	
?>