<?php
// Peanuts
// License: GPL

if(!defined('ROOT_DIR')) define('ROOT_DIR', __DIR__);
if(!defined('APP_DIR'))  define('APP_DIR', ROOT_DIR . '/app/');

require ROOT_DIR . '/lib/helpers.php';
require ROOT_DIR . '/lib/csvdb.php';

filter_set_config(ROOT_DIR . '/config.ini');
define('APP_NAME', 'app');
define('APP_TEMPLATE', CONFIG_TEMPLATE);



require APP_DIR . '/data.php';
require APP_DIR . '/actions.php';
require APP_DIR . '/shortcodes.php';
require APP_DIR . '/templates/' . APP_TEMPLATE . '/functions.php';


function initialize(){
	if(!filter_permitted_params(
		// GET params with regex
		[
			'uri'			=> '/^[a-z0-9_-]+$/',
			'post_uri'		=> '/^[a-z0-9_-]+$/',
			'patch_uri'		=> '/^[a-z0-9_-]+$/',
			'delete_uri'	=> '/^[a-z0-9_-]+$/',
			'post_id'		=> '/^\d+$/',
			'comment_id'	=> '/^\d+$/',
			'page'			=> '/^\d+$/',
			'hashtag'		=> '/^[a-zA-Z0-9_-]+$/',
			'slug'			=> '/^[a-z0-9_-]+$/'
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
			'post_id'		=> 'int',
			'comment_id'	=> 'int',
			'page'			=> 'int'
		],
		// POST typecast
		[ ]
	)) return get_404('Invalid URL component!');


	// Fills $_REQUEST['username']
	if(!filter_set_username()) return false;

	filter_set_flash();

	$response = filter_routes(
		// Get uri, with required params from $_REQUEST
		[ 	'login'				=> [],
			'new-post'			=> ['username'],
			'posts'				=> ['username'],
			'post'				=> ['username', 'post_id'],
			'edit-post'			=> ['username', 'post_id'],
			'edit-comment'		=> ['username', 'post_id', 'comment_id'],
			'hashtags'			=> ['username'],
			'page'				=> ['username', 'slug']
		],
		// Post uri, with required params from $_REQUEST
		[	'login'			=> ['post_username', 'post_team_password'],
			'logout'		=> [],
			'quick-post'	=> ['username', 'title'],
			'post'			=> ['username', 'title', 'body'],
			'comment'		=> ['username', 'post_id', 'body'],
		],
		// Patch (update) uri, with required params from $_REQUEST
		[	'post'		=> ['username', 'post_id', 'title', 'body'],
			'comment'	=> ['username', 'post_id', 'comment_id', 'body']
		],
		// Delete uri, with required params from $_REQUEST
		[	'post'		=> ['username', 'post_id'],
			'comment'	=> ['username', 'post_id', 'comment_id']
		]
	);

	// Routes
	if(!$response) return get_404('Invalid URL!');

	return $response;
}


// App init is not called in test environment
if(!defined('APP_ENV_IS_TEST')) initialize();

