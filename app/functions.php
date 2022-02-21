<?php
// Peanuts
// License: GPL






// 
// Routes
// 

function routes()
{
	// Post requests
	if(isset($_GET['post-uri'])){
		switch ($_GET['post-uri']) {
			case 'create-quick-post':
				return action_create_quick_post();

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
			return get_new_post();

		case 'posts':
			return get_posts();

		case '404':
			header404();
			return include_template('404.php');

		case 'login':
			return include_template('login.php');

		default:
			return redirectto('posts');
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



// Permitted GET and POST params
function filter_permitted_params()
{
	$param_names = [ 'uri', 'post-uri', 'id' ];
	foreach ($_GET as $key => $value) {
		if(!in_array($key, $param_names)){
			unset($_GET[$key]);
			continue;
		}

		if($_GET['id']) $_GET['id'] = intval($_GET['id']);
	}

	$param_names = [ 'username', 'team_password', 'title', 'body', 'id' ];
	foreach ($_POST as $key => $value) {
		if(!in_array($key, $param_names)){
			unset($_POST[$key]);
			continue;
		}

		if($_POST['id']) $_POST['id'] = intval($_POST['id']);
	}
}



function filter_set_username()
{
	$username = secure_cookie_get('username');

	if(!$username && $_GET['uri'] != 'login'){
		action_logout();
	} elseif($username) {
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
// Get functions
// 

function get_posts()
{
	include_template('posts.php', ['posts'=>data_post_list()]);
}


function get_new_post()
{
	include_template('new-post.php');
}


















// 
// Post functions
// 

function action_login()
{
	if(array_key_exists($_POST['username'], CONFIG_USERS) && md5($_POST['team_password']) == CONFIG_TEAM_PASSWORD){
		secure_cookie_set('username', $_POST['username']);
		redirectto('posts');
	} else {
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
	if(data_post_create()){
		flash_set('New post created!');
		redirectto('posts');
	} else {
		flash_set('Invalid/missing values!');
		get_new_post();
	}
}


function action_create_quick_post()
{
	if(data_post_create()){
		flash_set('New post created!');
		redirectto('posts');
	} else {
		flash_set('Invalid/missing values!');
		get_posts();
	}
}
