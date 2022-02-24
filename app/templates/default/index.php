<html>
<head>
	<title><?= pagetitle($template_name, $args); ?></title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_template_asset('style.css'); ?>" />
</head>
<body class='<?= $uri ;?> <?= $post_uri ;?>'>
	<?php if($uri == 'login'): ?>
	
		<?php render_partial('login.php'); ?>
	
	<?php else: ?>

		<div id='navbar' class='clear'>
			<div id='navbar-left'>
				<h1><?php linkto('posts', CONFIG_TEAM_NAME); ?></h1>
				<?php linkto('new-post', 'New Post', NULL, ['class' => 'btn btn-primary btn-sm']); ?>
				<span id='navbar-links'>
					<?php linkto('hashtags', 'Hashtags', NULL, ['class' => '']); ?>
				</span>
			</div>
			<div id='navbar-right'>
				<?php tag(CONFIG_USERS[$_REQUEST['username']], ['class'=>'text-dark medium'], 'span'); ?>
				<?= formto('logout'); ?>
					<button class='btn btn-sm btn-muted'>Logout</button>
				</form>
			</div>
		</div>
		<div id='main'>
			<?php tag($_REQUEST['flash'], ['id'=>'flash', 'class'=>'text-center']); ?>
			<?php render_partial($template_name, $args); ?>
		</div>
	
	<?php endif; ?>

	<script type="text/javascript" src='<?= urlto_template_asset('main.js'); ?>'></script>
</body>
</html>
