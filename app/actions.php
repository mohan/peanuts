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

	$username = secure_cookie_get('auth_username');

	if($username){
		// Update cookie authenticity
		secure_cookie_set('auth_username', $username);

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
	render('login.php', ['__pagetitle'=>'Login']);
}


function post_login()
{
	extract($_POST);

	if(array_key_exists($post_username, CONFIG_USERS) && md5($post_team_password) == CONFIG_TEAM_PASSWORD){
		secure_cookie_set('auth_username', $post_username);
		redirectto('posts');
	} else {
		redirectto('login');
	}
}


function post_logout()
{
	cookie_delete('auth_username');
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

	render('posts.php', [
		'__pagetitle'=>'Posts',
		'posts'=>$posts,
		'page'=>$page,
		'per_page' => $per_page
	]);
}


function get_post()
{
	extract($_GET);

	$post = data_post_read($post_id);
	if(!$post) return get_404();

	$comments = data_comment_list($post_id);

	render('post.php', [
		'__pagetitle'=>substr($post['title'], 0, 20),
		'post'=>$post,
		'comments'=>$comments
	]);
}


function get_new_post()
{
	render('post-editor.php', ['__pagetitle'=>'New Post']);
}


function get_edit_post()
{
	_check_if_current_user_can_edit_post();

	extract($_GET);

	$post = data_post_read($post_id);
	if(!$post) return get_404();

	render('post-editor.php', ['__pagetitle' => "Edit Post #$post_id", 'post' => $post]);
}


function get_edit_comment()
{
	_check_if_current_user_can_edit_comment();

	extract($_GET);

	$comment = data_comment_read($post_id, $comment_id);
	if(!$comment) return get_404();

	render('comment-editor.php', [
		'__pagetitle' => "Edit Comment #".$comment_id,
		'comment' => $comment,
		'post_id' => $post_id
	]);
}


function get_hashtags()
{
	extract($_GET);

	$data = data_post_hashtags($hashtag);

	$data['__pagetitle'] = ($hashtag ? "#$hashtag - " : '') . 'Hashtags';
	render('hashtags.php', $data);
}


function get_page()
{
	extract($_GET);

	if(!in_array($slug, ['readme', 'markdown', 'shortcodes'])) return get_404();

	if($slug == 'readme'){
		$text = file_get_contents("./readme.md");
	} else {
		$pages_path = './' . APP_NAME . '/pages';
		$text = file_get_contents("$pages_path/$slug.md");
	}

	render('page.php', ['__pagetitle' => ucfirst($slug), 'text'=>$text]);
}














// 
// Post functions
// 


function post_create_post()
{
	extract($_REQUEST);

	$post_id = data_post_create( $username, $title, $body );
	if($post_id){
		flash_set('New post created!');
		redirectto('post', ['post_id'=>$post_id]);
	} else {
		flash_set('Invalid/missing values!', true);
		get_new_post();
	}
}


function post_create_quick_post()
{
	extract($_REQUEST);

	if(data_post_create( $username, $title, false )){
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

	if(data_post_update( $post_id, $title, $body )){
		flash_set('Post updated!');
		redirectto('post', ['post_id'=>$post_id]);
	} else {
		flash_set('Invalid/missing values!', true);
		get_edit_post();
	}
}


function post_create_comment()
{
	extract($_REQUEST);

	if(data_comment_create( $username, $post_id, $body )){
		flash_set('Comment added!');
		redirectto('post', ['post_id'=>$post_id, '__hash'=>'comments']);
	} else {
		flash_set('Invalid/missing values!', true);
		get_post();
	}
}


function post_update_comment()
{
	_check_if_current_user_can_edit_comment();

	extract($_REQUEST);

	if(data_comment_update( $post_id, $comment_id, $body )){
		flash_set('Comment updated!');
		redirectto('post', ['post_id'=>$post_id]);
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
	extract($_REQUEST);

	$current_post_record = data_post_read($post_id, ['username']);
	if($current_post_record['username'] != $username) {
		get_404();
	}
}


function _check_if_current_user_can_edit_comment()
{
	extract($_REQUEST);
	
	$current_comment_record = data_comment_read($post_id, $comment_id, ['username']);
	if($current_comment_record['username'] != $username) {
		get_404();
	}
}
