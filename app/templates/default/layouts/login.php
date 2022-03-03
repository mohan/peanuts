<html>
<head>
	<title>Login - <?= CONFIG_APP_TITLE ?></title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('assets/default/style.css'); ?>" />
</head>
<body>
	<div class='panel' style='margin-top:30px;'>
		<?= html_app_title(); ?>
		<?= formto('login', NULL, ['class'=>'body']); ?>
			<?php if(CONFIG_MULTI_TEAMS): ?>
				<select name='post_teamname' class='input'>
					<option disabled selected>Select Team</option>
					<option value='primary'>Primary Team</option>
					<?php foreach (CONFIG_TEAMS as $teamname_key => $teamname): ?>
						<?= tag($teamname, ['value'=>$teamname_key], 'option'); ?>
					<?php endforeach; ?>
				</select>
			<?php endif; ?>
			<label class='d-block' for='username'>Username</label>
			<input name='post_username' type='text' id='username' autocomplete='false' autocorrect='false' />
			<label class='d-block' for='team_password'>Team Password</label>
			<input name='post_team_password' type='password' id='team_password' />
			<div class='label'>
				<input type='submit' value='Login' class='btn'></button>
			</div>
		</form>
	</div>
</body>
</html>
