<div id="registrationForm">
	<form method="POST">
		<h2>Account</h2>
		<p>Pick your username and password.</p>
		<ul>
			<li><label for="username">Username:</label><input type="text" name="username" id="username"/></li>
			<li><label for="password">Password:</label><input type="password" name="password" id="password"/></li>
			<li><label for="passwordConfirm">Confirm Password:</label><input type="password" name="passwordConfirm" id="passwordConfirm"/></li>
		</ul>
		<h2>Interests</h2>
		<p>Tell us what regions you care about so that we can create your episodes.</p>
		<?php
			$regions = Interest::getInterests(Interest::REGION);
			$topics = Interest::getInterests(Interest::TOPIC);
		?>
		<div id="regions">
			<select id="regions" name="regions[]" multiple="multiple" size="10">
				<?php foreach($regions as $region) {
					?>
					<option value="<?php echo($region->getInterestID()); ?>"><?php echo($region->getName()); ?></option>
					<?php
				} ?>
			</select>
		</div>
		<input type="submit" value="Create Account"/>
	</form>
</div>