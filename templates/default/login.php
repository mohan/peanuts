<div id='login' class='panel'>
	<h1 class='heading'><?php echo CONFIG_APP_TITLE;?></h1>
	<form class='body' method='post' action='<?php echo urlto('login', NULL, 'post');?>'>
		<?php if($_REQUEST['PEANUTS']['flash']): ?>
			<div id='flash'><?php echo $_REQUEST['PEANUTS']['flash']; ?></div>
		<?php endif; ?>
		<?php if(CONFIG_MULTI_TEAMS): ?>
			<select name='teamname' class='input'>
				<option disabled selected>Select Team</option>
				<option value='default'>Default Team</option>
				<?php foreach (CONFIG_TEAMS as $teamname_key => $teamname): ?>
					<option value='<?php echo $teamname_key;?>'><?php echo $teamname; ?></option>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>
		<label class='d-block' for='username'>Username</label>
		<input name='username' type='text' id='username' autocomplete='false' autocorrect='false' />
		<label class='d-block' for='team_password'>Team Password</label>
		<input name='team_password' type='password' id='team_password' />
		<div class='label'>
			<input type='submit' value='Login' class='btn'></button>
		</div>
	</form>
</div>
