<div>
	<?php
		$episodeID = isset($_GET['e'])?$_GET['e']:0;
		$episode = EpisodeFactory::getObject($episodeID);
	
		if($episode->getEpisodeID() == 0) {
			?>
			Unknown episode ID
			<?php
		} else {
			?>
			<script type="text/javascript">
				<?php
					$clips = $episode->getClips();
					$encodedClips = array();
					foreach($clips as $clip) {
						$clipArray = array();
						$clipArray['feedURL'] = $clip->getFeedURL();
						$clipArray['contextURL'] = $clip->getContextURL();
						$clipArray['title'] = $clip->getTitle();
						$clipArray['description'] = $clip->getDescription();
						$clipArray['thumbnail'] = html_entity_decode($clip->getThumbnail());
						$encodedClips[] = json_encode($clipArray);
					}
				?>
				$(function() {
					$player = $("#player");
					$player.attnPlayer();
					$player.attnPlayer("load", [<?php echo(implode($encodedClips,","));?>]);
				});
			</script>
			<h1 id="episodeTitle">Episode: <?php echo($episode->getTitle());?></h1>
			<?php
				if(User::isLoggedIn()) {
					?>
					<div class="nav"><a href = "?p=episode&a=list">Back to Your Episodes</a></div>
					<?php
				}
			?>
			<div id="player"></div>
			<?php
		}
	?>
</div>