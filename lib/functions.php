<?php
// License: GPL

// 
// Routes
// 

function routes()
{
	// Post requests
	if(isset($_GET['post-uri'])){
		switch ($_GET['post-uri']) {
			case 'create-post':
				return action_create_post();

			case 'login':
				return action_login();

			case 'logout':
				return action_logout();

			default:
				return redirectto('404');
		}
	}

	// Get requests
	switch ($_GET['uri']) {
		case 'new-post':
			return include_template('new-post.php');

		case 'posts':
			return include_template('posts.php');

		case '404':
			header404();
			return include_template('404.php');

		case 'login':
			return include_template('login.php');

		default:
			return redirectto('login');
	}
}









// 
// Filters
// 

function filter_set_config(){
	$config = parse_ini_file('./config.ini');

	foreach ($config as $key => $value) {
		define('CONFIG_' . $key, $value);
	}
}


function filter_set_username()
{
	if($_GET['uri'] == 'login'){
		cookie_delete('username');
	}

	$username = secure_cookie_get('username');

	if(!$username && $_GET['uri'] != 'login'){
		action_logout();
	} else {
		secure_cookie_set('username', $username);
		$_REQUEST['PEANUTS'] = [
			'username' => $username,
			'teamname' => NULL
		];
	}
}


function filter_set_flash()
{
	$flash = secure_cookie_get('flash');

	if($flash){
		$_REQUEST['PEANUTS']['flash'] = $flash;
		cookie_delete('flash');
	}
}






// 
// Actions
// 

function action_login()
{
	if(array_key_exists($_POST['username'], CONFIG_USERS) && md5($_POST['team_password']) == CONFIG_TEAM_PASSWORD){
		secure_cookie_set('username', $_POST['username']);
		redirectto('posts');
	} else {
		flash_set('Incorrect username or team password!');
		redirectto('login');
	}
}


function action_logout()
{
	cookie_delete('username');
	redirectto('login');
}


function action_create_post()
{
	global $csvdb_tables;
	$values = [
		username => $_REQUEST['PEANUTS']['username'],
		title => $_POST['title'],
		body => $_POST['body'],
	];

	if(csvdb_create_record($csvdb_tables['posts'], $values)){
		flash_set('New post created!');
		redirectto('posts');
	} else {
		flash_set('Invalid values!');
		redirectto('new-post');
	}
}



// 
// CSVDB
// 

$csvdb_tables = [];

function csvdb_init()
{
	global $csvdb_tables;

	$csvdb_tables['posts'] = [
		"data_dir" => CONFIG_DATA_DIR,
		"tablename" => 'posts.csv',
		"max_record_width" => 200,
		"columns" => [
			"username"=>"string",
			"title"=>"string",
			"body"=>"string"
		],
		"validations_callback" => "csvdb_posts_table_validations",
		"auto_timestamps" => true,
	];
}

function csvdb_posts_table_validations($r_id, $values, $t)
{
	if(!$values['username'] || !$values['title'] || !$values['body'] ||
		strlen($values['username']) > 20 ||
		strlen($values['title']) > 50 ||
		strlen($values['body']) > 128
	) return false;

	return true;
}
