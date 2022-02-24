<?php
// Peanuts
// License: GPL

// 
// Filters
// 

function filter_set_username()
{
	// Exclude login check
	if($_GET['uri'] == 'login' ||
		$_GET['post_uri'] == 'login'
	) return;

	$username = secure_cookie_get('u');

	if($username){
		// Update cookie authenticity
		secure_cookie_set('u', $username);

		// Set request username
		$_REQUEST['username'] = $username;
		$_REQUEST['teamname'] = NULL;
	} else {
		redirectto('login');
	}
}



// 
// Login
// 



function get_login()
{
	render('login.php');
}


function post_login()
{
	extract($_POST);

	if(array_key_exists($username, CONFIG_USERS) && md5($team_password) == CONFIG_TEAM_PASSWORD){
		secure_cookie_set('u', $username);
		redirectto('posts');
	} else {
		redirectto('login');
	}
}


function post_logout()
{
	cookie_delete('u');
	redirectto('login');
}











// 
// Get functions
// 

function get_root()
{
	redirectto('posts');
}


function get_posts()
{
	extract($_GET);

	$page = $page ? $page : 1;
	$per_page = 30;
	$posts = data_post_list($page, $per_page);

	render('posts.php', ['posts'=>$posts, 'page'=>$page, 'per_page' => $per_page]);
}


function get_post()
{
	extract($_GET);

	$post = data_post_read($id);
	if(!$post) return get_404();

	$comments = data_comment_list($id);

	render('post.php', ['id'=>$id, 'post'=>$post, 'comments'=>$comments]);
}


function get_new_post()
{
	render('post-editor.php');
}


function get_edit_post()
{
	_check_if_current_user_can_edit_post();

	extract($_GET);

	$post = data_post_read($id);
	if(!$post) return get_404();

	render('post-editor.php', $post);
}


function get_edit_comment()
{
	_check_if_current_user_can_edit_comment();

	extract($_GET);

	$comment = data_comment_read($post_id, $id);
	if(!$comment) return get_404();

	render('comment-editor.php', $comment);
}


function get_hashtags()
{
	extract($_GET);

	render('hashtags.php', data_post_hashtags($hashtag));
}















// 
// Post functions
// 


function post_create_post()
{
	extract($_REQUEST);

	if(data_post_create( $_REQUEST['username'], $title, $body )){
		flash_set('New post created!');
		redirectto('posts');
	} else {
		flash_set('Invalid/missing values!', true);
		get_new_post();
	}
}


function post_create_quick_post()
{
	extract($_REQUEST);

	if(data_post_create( $_REQUEST['username'], $title, false )){
		flash_set('New post created!');
		redirectto('posts');
	} else {
		flash_set('Invalid/missing values!', true);
		get_posts();
	}
}


function post_update_post()
{
	_check_if_current_user_can_edit_post();

	extract($_REQUEST);

	if(data_post_update( $id, $title, $body )){
		flash_set('Post updated!');
		redirectto('post', ['id'=>$id]);
	} else {
		flash_set('Invalid/missing values!', true);
		get_edit_post();
	}
}


function post_create_comment()
{
	extract($_REQUEST);

	if(data_comment_create( $_REQUEST['username'], $id, $body )){
		redirectto('post', ['id'=>$id, '__hash'=>'comments']);
	} else {
		flash_set('Invalid/missing values!', true);
		get_post();
	}
}


function post_update_comment()
{
	_check_if_current_user_can_edit_comment();

	extract($_REQUEST);

	if(data_comment_update( $post_id, $id, $body )){
		flash_set('Comment updated!');
		redirectto('post', ['id'=>$post_id]);
	} else {
		flash_set('Invalid/missing values!', true);
		get_edit_comment();
	}
}





// 
// Internal
// 

function _check_if_current_user_can_edit_post()
{
	extract($_GET);

	$current_post_record = data_post_read($id, ['username']);
	if($current_post_record['username'] != $_REQUEST['username']) {
		get_404();
	}
}


function _check_if_current_user_can_edit_comment()
{
	extract($_GET);
	
	$current_comment_record = data_comment_read($post_id, $id, ['username']);
	if($current_comment_record['username'] != $_REQUEST['username']) {
		get_404();
	}
}
