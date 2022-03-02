<?php
require ROOT_DIR . '/lib/helpers.php';
require ROOT_DIR . '/lib/csvdb.php';

filter_set_config(ROOT_DIR . '/config.ini');
define('APP_NAME', 'app');
define('APP_TEMPLATE', CONFIG_TEMPLATE);

require APP_DIR . '/data.php';
require APP_DIR . '/actions.php';
require APP_DIR . '/shortcodes.php';
require APP_DIR . '/templates/' . APP_TEMPLATE . '/functions.php';

function app_init(){
	if(!filter_permitted_params(
		// GET params with regex
		[
			'uri'			=> '/^[a-z\d_\/-]+$/',
			'post_uri'		=> '/^[a-z\d_\/-]+$/',
			'patch_uri'		=> '/^[a-z\d_\/-]+$/',
			'delete_uri'	=> '/^[a-z\d_\/-]+$/',
			'post_id'		=> '/^\d+$/',
			'comment_id'	=> '/^\d+$/',
			'page'			=> '/^\d+$/',
			'hashtag'		=> '/^[a-zA-Z\d_-]+$/',
			'slug'			=> '/^[a-z\d_-]+$/'
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
			'auth'	=> 64,
			'flash'	=> 256
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
			'post/find'			=> ['username', 'post_id'],
			'edit-post'			=> ['username', 'post_id'],
			'edit-comment'		=> ['username', 'post_id', 'comment_id'],
			'hashtags'			=> ['username'],
			'page'				=> ['username', 'slug'],
			'trash/posts'		=> [],
			'trash/comments'	=> []
		],
		// Post uri, with required params from $_REQUEST
		[	'login'			=> ['post_username', 'post_team_password'],
			'logout'		=> [],
			'quick-post'	=> ['username', 'title'],
			'post'			=> ['username', 'title', 'body'],
			'comment'		=> ['username', 'post_id', 'body'],
		],
		// Patch (update) uri, with required params from $_REQUEST
		[	'post'				=> ['username', 'post_id'],
			'comment'			=> ['username', 'post_id', 'comment_id'],
			'trash/post'		=> ['username', 'post_id'],
			'trash/comment'		=> ['username', 'post_id', 'comment_id']
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