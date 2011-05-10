<div id="registrationForm">
	<form method="POST">
		<h2>Account</h2>
		<p>Pick your username and password.</p>
		<ul>
			<li><label for="username">Username:</label><input type="text" name="username" id="username"/></li>
			<li><label for="password">Password:</label><input type="password" name="password" id="password"/></li>
		</ul>
		<h2>Interests</h2>
		<p>Tell us everything what you want to know about.</p>
		<?php
			$regions = Interest::getInterests(Interest::REGION);
			$topics = Interest::getInterests(Interest::TOPIC);
		?>
		<ul>
			<li>
				<div id="regions">
					<label>Regions:</label>
					<select id="regions" name="regions[]" multiple="multiple" size="10">
						<?php foreach($regions as $region) {
							?>
							<option value="<?php echo($region->getInterestID()); ?>"><?php echo($region->getName()); ?></option>
							<?php
						} ?>
					</select>
				</div>
			</li>
			<li><label>&nbsp;</label><input type="submit" value="Create Account"/></li>
		</ul>
	</form>
</div>