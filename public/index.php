<?php
// Peanuts
// License: GPL

define('ROOT_DIR', __DIR__ . '/../');
define('APP_DIR', ROOT_DIR . '/app/');

require ROOT_DIR . '/lib/helpers.php';
filter_set_config(ROOT_DIR . '/data/config.ini');

require APP_DIR . '/init.php';


/*
*
* Please uncomment the below line (`app_init`) to start using the app.
*
* No warranties/liabilities apply as per GPL license. You are responsible for your own awesomeness!
*
*/
/* app_init(); */

/*
*
* Please delete all the below lines as well.
*
*/
?>

<html>
<head>
	<title>Peanuts</title>
	<link rel="stylesheet" type="text/css" href="<?= urlto_public_dir('assets/default/style.css'); ?>" />
</head>
<body>
	<div id='main'>
		<div id='post'>
			<h3 class='title'>Peanuts Readme</h3>
			<?= tag(file_get_contents(ROOT_DIR . 'readme.md'), ['class'=>'markdown'], 'pre'); ?>
		</div>
</body>
</html>
