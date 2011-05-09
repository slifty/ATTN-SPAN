<?php

	$episodes = User::$currentUser->getEpisodes(true);
	
	if(sizeof($episodes) == 0) {
		$start = time() - 60*60*24*75; // Generate 60 episodes
		$end = time() - 60*60*24*15; // Start 2 weeks ago
		?>
		Looks like you don't have any episodes yet!
		<a href="?p=episode&a=generate&s=<?php echo(urlencode(date('m/d/Y',$start)))?>&e=<?php echo(urlencode(date('m/d/Y',$end)))?>">Generate some.</a>
		<?php
	} else {
			$firstEpisode = User::$currentUser->getFirstEpisode();
			$lastEpisode = User::$currentUser->getLastEpisode();
		?>
		<h2>Episodes from <?php echo(date("M jS, Y",$firstEpisode->getDateBased())); ?> to <?php echo(date("M jS, Y",$lastEpisode->getDateBased())); ?></h2>
		<div id="episodes">
			<?php
			foreach($episodes as $episode) {
				?>
				<div class='episode'>
					<div class="title"><a href="?p=episode&a=view&e=<?php echo($episode->getEpisodeID());?>"><?php echo($episode->getTitle());?></a></div>
					<div class="clipCount"><?php echo(($episode->getClipCount() == 1)?"1 clip":$episode->getClipCount()." clips"); ?></div>
				</div>
				<?php
			}
			?>
			<div class='episode'>
				<a href="?p=episode&a=generate&s=<?php echo(urlencode(date('m/d/Y',$firstEpisode->getDateBased() - 60*60*24*15)))?>&e=<?php echo(urlencode(date('m/d/Y',$firstEpisode->getDateBased())))?>">Generate more</a>
			</div>
		</div>
		<?php
	}
	
?>