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
			<div class='episode'>
				<div class="title"><a href="?p=episode&a=view&e=<?php echo($episode->getEpisodeID());?>"><?php echo($episode->getTitle());?></a></div>
				<div class="clipCount"><?php echo(($episode->getClipCount() == 1)?"1 clip":$episode->getClipCount()." clips"); ?></div>
			</div>
			<?php
		}
	}
	
?>