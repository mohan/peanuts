<?php
// Peanuts
// License: GPL

define('ROOT_DIR', __DIR__ . '/../');
define('APP_DIR', ROOT_DIR . '/app/');

require ROOT_DIR . '/lib/helpers.php';
filter_set_config(ROOT_DIR . '/data/config.ini');

require APP_DIR . '/init.php';
app_init();
