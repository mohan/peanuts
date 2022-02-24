<?php
// Peanuts
// License: GPL

require_once './lib/helpers.php';
require_once './lib/csvdb.php';
require_once './app/data.php';
require_once './app/actions.php';

filter_set_config('./config.ini');

define('APP_NAME', 'app');
define('APP_TEMPLATE', CONFIG_TEMPLATE);

require_once './app/templates/' . APP_TEMPLATE . '/functions.php';


filter_permitted_params(
	// GET params with max_length
	[ 'uri' => 20, 'post_uri' => 20, 'id' => 8, 'post_id' => 8, 'page' => 8, 'hashtag' => 64 ],
	// POST params with max_length
	[ 'username' => 20, 'team_password' => 20, 'team_name' => 20, 'title' => 1024, 'body' => 8192 ],
	// COOKIE params with max_length
	[ 'u' => 64, 'flash' => 256 ],
	// GET typecast
	[ 'id' => 'int', 'post_id' => 'int', 'page' => 'int' ],
	// post typecast
	[ ]
);
filter_set_flash();
filter_set_username();

// Routes
filter_routes(
	// Get uri
	[ 'login', 'new-post', 'posts', 'post', 'edit-post', 'edit-comment', 'hashtags' ],
	// Post uri
	[ 'login', 'logout', 'create-quick-post', 'create-post', 'update-post', 'create-comment', 'update-comment' ]
);
