<?php
// Peanuts
// License: GPL


// 
// Filters
// 

function filter_set_username()
{
	$username = secure_cookie_get('username');

	if(!$username && $_GET['uri'] != 'login'){
		post_logout();
	} elseif($username) {
		secure_cookie_set('username', $username);
		$_REQUEST['APP'] = [
			'username' => $username,
			'teamname' => NULL
		];
	}
}


















// 
// Get functions
// 

function get_404()
{
	return include_template('404.php');
}


function get_login()
{
	return include_template('login.php');
}


function get_posts()
{
	$page = $_GET['page'] ? $_GET['page'] : 1;
	$per_page = 30;
	$posts = data_post_list($page, $per_page);

	include_template('posts.php', ['posts'=>$posts, 'page'=>$page, 'per_page' => $per_page]);
}


function get_post()
{
	$post = data_post($_GET['id']);
	if(!$post) get_404();
	else include_template('post.php', ['post'=>$post]);
}


function get_new_post()
{
	include_template('new-post.php');
}


















// 
// Post functions
// 

function post_login()
{
	if(array_key_exists($_POST['username'], CONFIG_USERS) && md5($_POST['team_password']) == CONFIG_TEAM_PASSWORD){
		secure_cookie_set('username', $_POST['username']);
		redirectto('posts');
	} else {
		redirectto('login');
	}
}


function post_logout()
{
	cookie_delete('username');
	redirectto('login');
}


function post_create_post()
{
	if(data_post_create()){
		flash_set('New post created!');
		redirectto('posts');
	} else {
		flash_set('Invalid/missing values!', true);
		get_new_post();
	}
}


function post_create_quick_post()
{
	if(data_post_create()){
		flash_set('New post created!');
		redirectto('posts');
	} else {
		flash_set('Invalid/missing values!', true);
		get_posts();
	}
}
