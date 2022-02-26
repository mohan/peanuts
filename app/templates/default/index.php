<html>
<head>
	<title>
		<?= $__pagetitle; ?> -
		<?= in_array($template_name, ['login.php', '404.php']) ? CONFIG_APP_TITLE : CONFIG_TEAM_NAME; ?>
	</title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_template_asset('style.css'); ?>" />
</head>
<body class='<?= $uri ;?> <?= $post_uri ;?> <?= $uri == 'post' && $post['body'] ? 'post-with-body' : ''; ?>'>
	<?php if($uri == 'login'): ?>
	
		<?php render_partial('login.php'); ?>
	
	<?php else: ?>

		<div id='navbar' class='clear'>
			<div id='navbar-left'>
				<h1><?= linkto('posts', CONFIG_TEAM_NAME); ?></h1>
				<?= linkto('new-post', 'New Post', NULL, ['class' => 'btn btn-primary btn-sm']); ?>
				<span id='navbar-links'>
					<?= linkto('hashtags', 'Hashtags', NULL, ['class' => '']); ?>
				</span>
			</div>
			<div id='navbar-right'>
				<?= tag(CONFIG_USERS[$_REQUEST['username']], ['class'=>'text-dark medium'], 'span'); ?>
				<?= formto('logout'); ?>
					<button class='btn btn-sm btn-muted'>Logout</button>
				</form>
			</div>
		</div>
		<div id='main' class='m-b p-b border-bottom'>
			<?php render_partial($template_name, $args); ?>
		</div>

		<div class='m-b p-b text-center text-muted small'>
			<?= linkto('page', 'Readme', ['slug'=>'readme'], ['class'=>'text-muted']); ?> /
			<?= linkto('page', 'Markdown', ['slug'=>'markdown'], ['class'=>'text-muted']); ?> /
			<?= linkto('page', 'Shortcodes', ['slug'=>'shortcodes'], ['class'=>'text-muted']); ?>
		</div>
	
	<?php endif; ?>

	<script type="text/javascript" src='<?= urlto_template_asset('main.js'); ?>'></script>
</body>
</html>
