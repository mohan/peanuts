<?php
// Peanuts
// License: GPL

require_once './lib/helpers.php';

filter_set_config('./config.ini');
define('APP_NAME', 'app');
define('APP_TEMPLATE', CONFIG_TEMPLATE);

require_once './lib/csvdb.php';
require_once './app/data.php';
require_once './app/actions.php';




filter_permitted_params(
	[ 'uri', 'post_uri', 'id', 'post_id', 'page' ],		// GET params
	[ 'username', 'team_password', 'title', 'body' ],	// POST params
	[ 'username', 'flash' ],							// COOKIE params
	[ 'id' => 'int', 'page' => 'int' ],					// GET typecast
	[ ]													// post typecast
);
filter_set_flash();
filter_set_username();


// data
data_init();

require_once './app/templates/' . APP_TEMPLATE . '/functions.php';
// Routes
filter_routes(
	[ 'login', 'new-post', 'posts', 'post', 'edit-post', 'edit-comment' ],
	[ 'login', 'logout', 'create-quick-post', 'create-post', 'update-post', 'create-comment', 'update-comment' ]
);
