<html>
<head>
	<title>404 - <?= CONFIG_APP_TITLE ?></title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('assets/default/style.css'); ?>" />
</head>
<body>
	<div class='panel' style='margin-top:30px;'>
		<?= html_app_title(); ?>
		<div class='body'>
			Page not found!

			<?= tag($message, [], 'p') ?>
		</div>
	</div>
</body>
</html>
