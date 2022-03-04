<html>
<head>
	<title><?= $__pagetitle; ?> - <?= CONFIG_TEAM_NAME ?></title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('assets/default/style.css'); ?>" />
</head>
<body class='<?= $uri ;?> <?= $post_uri ;?> <?= $uri == 'post' && $post['body'] ? 'post-with-body' : ''; ?>'>
	<div id='navbar' class='clear'>
		<div id='navbar-left'>
			<h1><?= linkto('', CONFIG_TEAM_NAME); ?></h1>
			<?= linkto('new-post', 'New Post', NULL, ['class' => 'btn btn-primary btn-sm']); ?>
			<span id='navbar-links'>
				<?= linkto('hashtags', 'Hashtags', NULL, ['class' => '']); ?>
			</span>
			<form id='form-post-find' action="<?= urltoget('post/find') ?>" method='get' class='m-l'>
				<?= tag('', ['name'=>'uri', 'type'=>'hidden', 'value'=>'post/find'], 'input') ?>
				<?= tag($_GET['post_id'] ? 'Post #' . $_GET['post_id'] : '', ['name'=>'post_id', 'type'=>'text', placeholder=>'Find by post #id', 'class'=>'d-inline small input-text-toggle-clear'], 'input') ?>
				<?= tag('', ['type'=>'submit', 'value'=>'Go', 'class'=>'d-inline btn btn-sm'], 'input') ?>
			</form>
		</div>
		<div id='navbar-right'>
			<span class='text-dark medium'>
				<?= tag(CONFIG_USERS[$_REQUEST['username']], ['id'=>'navbar-username'], 'span'); ?>
			</span>
			<?= formto('logout'); ?>
				<button class='btn btn-sm btn-muted'>Logout</button>
			</form>
		</div>
	</div>
	<div id='main' class='m-b p-b border-bottom'>
		<?php render_partial($template_name, $args); ?>
	</div>

	<div class='m-b p-b text-center text-muted small'>
		<p>
			<?= linkto('trash/posts', 'Trash', [], ['class'=>'text-muted']); ?>
		</p>
		<?= linkto('page', 'Readme', ['slug'=>'readme'], ['class'=>'text-muted']); ?> /
		<?= linkto('page', 'Markdown', ['slug'=>'markdown'], ['class'=>'text-muted']); ?> /
		<?= linkto('page', 'Shortcodes', ['slug'=>'shortcodes'], ['class'=>'text-muted']); ?>
	</div>

	<script type="text/javascript" src='<?= urlto_public_dir('assets/default/main.js'); ?>'></script>
</body>
</html>
