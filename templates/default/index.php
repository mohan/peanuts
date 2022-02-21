<html>
<head>
	<title><?php echo pagetitle($template_name); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo urlto_template_asset('asset_style.css'); ?>" />
</head>
<body>
	<?php if($_GET['uri'] == 'login'): ?>
	
		<?php include $template_path . $template_name; ?>
	
	<?php else: ?>

		<div id='navbar' class='clear'>
			<div id='navbar-left'>
				<h1><?php linkto(CONFIG_TEAM_NAME, 'posts'); ?></h1>
				<?php linkto('New Post', 'new-post', NULL, 'btn btn-primary'); ?>
			</div>
			<div id='navbar-right'>
				<?php echo CONFIG_USERS[$_REQUEST['PEANUTS']['username']]; ?>
				<form method='post' action='<?php echo urlto('logout', NULL, 'post'); ?>'>
					<button class='btn btn-muted'>Logout</button>
				</form>
			</div>
		</div>
		<div id='main'>
			<?php if($_REQUEST['PEANUTS']['flash']): ?>
				<div id='flash' class='text-center'><?php echo htmlentities($_REQUEST['PEANUTS']['flash']); ?></div>
			<?php endif; ?>
			<?php include $template_path . $template_name; ?>
		</div>
	
	<?php endif; ?>

	<script type="text/javascript" src='<?php echo urlto_template_asset('asset_js.js'); ?>'></script>
</body>
</html>
