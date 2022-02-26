<?php
// Peanuts
// License: GPL

require './lib/helpers.php';
require './lib/csvdb.php';
require './app/data.php';
require './app/actions.php';
require './app/shortcodes.php';

filter_set_config('./config.ini');

define('APP_NAME', 'app');
define('APP_TEMPLATE', CONFIG_TEMPLATE);

require './app/templates/' . APP_TEMPLATE . '/functions.php';


filter_permitted_params(
	// GET params with regex
	[
		'uri'		=> '/^[a-z0-9_-]+$/',
		'post_uri'	=> '/^[a-z0-9_-]+$/',
		'post_id'	=> '/^\d+$/',
		'comment_id'=> '/^\d+$/',
		'page'		=> '/^\d+$/',
		'hashtag'	=> '/^[a-zA-Z0-9_-]+$/',
		'slug'		=> '/^[a-z0-9_-]+$/'
	],
	// POST params with max_length
	[
		'post_username'			=> 20,
		'post_team_password'	=> 20,
		'post_teamname'			=> 20,
		'title'					=> 1024,
		'body'					=> 8192
	],
	// COOKIE params with max_length
	[
		'auth_username'	=> 64,
		'auth_flash'	=> 256
	],
	// GET typecast
	[
		'id'		=> 'int',
		'post_id'	=> 'int',
		'page'		=> 'int'
	],
	// POST typecast
	[ ]
);


filter_set_flash();
filter_set_username();

// Routes
filter_routes(
	// Get uri, with required params from $_REQUEST
	[ 	'login'				=> [],
		'new-post'			=> [],
		'posts'				=> [],
		'post'				=> ['post_id'],
		'edit-post'			=> ['post_id'],
		'edit-comment'		=> ['post_id', 'commment_id'],
		'hashtags'			=> [],
		'page'				=> ['slug']
	],
	// Post uri, with required params from $_REQUEST
	[	'login'				=> ['post_username', 'post_team_password'],
		'logout'			=> [],
		'create-quick-post' => ['title'],
		'create-post'		=> ['title', 'body'],
		'update-post'		=> ['title', 'body'],
		'create-comment'	=> ['post_id', 'body'],
		'update-comment'	=> ['post_id', 'comment_id', 'body']
	]
);
