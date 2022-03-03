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
	) return true;

	$username = secure_cookie_get('auth');

	if($username){
		// Update cookie authenticity
		secure_cookie_set('auth', $username);

		// Set request username
		$_REQUEST['username'] = $username;
		$_REQUEST['teamname'] = NULL;

		return true;
	} else {
		redirectto('login');
		return false;
	}
}



// 
// Login
// 



function get_login()
{
	if(isset($_COOKIE['auth'])) cookie_delete('auth');
	return render('layouts/login.php', ['__pagetitle'=>'Login'], false);
}


function post_login()
{
	extract($_POST);

	if(array_key_exists($post_username, CONFIG_USERS) && md5($post_team_password) == CONFIG_TEAM_PASSWORD){
		secure_cookie_set('auth', $post_username);
		return redirectto('posts');
	} else {
		return redirectto('login');
	}
}


function post_logout()
{
	cookie_delete('auth');
	return redirectto('login');
}











// 
// Get functions
// 

function get_root()
{
	return redirectto('posts');
}


function get_posts()
{
	extract($_GET);

	$page = $page ? $page : 1;
	$per_page = 30;
	$posts = data_post_list($page, $per_page);

	$banner_post = $page == 1 ? data_post_read(CONFIG_BANNER_POST_ID, ['title', 'body']) : false;

	return render('posts/posts.php', [
		'__pagetitle'=>'Posts',
		'posts'=>$posts,
		'page'=>$page,
		'per_page' => $per_page,
		'banner_post' => $banner_post
	]);
}


function get_post_find()
{
	extract($_GET);

	$post = data_post_read($post_id, ['id']);
	if(!$post){
		flash_set("Post #$post_id not found!");
		return redirectto('posts');
	}

	return redirectto('post', ['post_id'=>$post_id]);
}


function get_post()
{
	extract($_GET);

	$post = data_post_read($post_id);
	if(!$post) return get_404('Post not found.');

	$comments = data_comment_list($post_id);
	
	return render('posts/post.php', [
		'__pagetitle'=>substr($post['title'], 0, 20),
		'post'=>$post,
		'comments'=>$comments
	]);
}


function get_new_post()
{
	return render('posts/post-editor.php', ['__pagetitle'=>'New Post']);
}


function get_edit_post()
{
	if(!_can_current_user_edit_post()) return get_404('No post found.');

	extract($_GET);

	$post = data_post_read($post_id);
	if(!$post) return get_404('Post not found.');

	return render('posts/post-editor.php', ['__pagetitle' => "Edit Post #$post_id", 'post' => $post]);
}


function get_edit_comment()
{
	if(!_can_current_user_edit_comment()) return get_404('No comment found.');

	extract($_GET);

	$comment = data_comment_read($post_id, $comment_id);
	if(!$comment) return get_404();

	return render('comments/comment-editor.php', [
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
	return render('posts/hashtags.php', $data);
}


function get_page()
{
	extract($_GET);

	if(!in_array($slug, ['readme', 'markdown', 'shortcodes'])) return get_404();

	if($slug == 'readme'){
		$text = file_get_contents(ROOT_DIR . "/readme.md");
	} else {
		$pages_path = APP_DIR . '/pages';
		$text = file_get_contents("$pages_path/$slug.md");
	}

	return render('posts/page.php', ['__pagetitle' => ucfirst($slug), 'text'=>$text]);
}


function get_trash_posts()
{
	$data = data_trash_post_list();
	return render('posts/trash.php', ['__pagetitle' => 'Trash - Posts', 'data'=>$data]);
}


function get_trash_comments()
{
	$data = data_trash_comments_list();
	return render('comments/trash.php', ['__pagetitle' => 'Trash - Comments', 'data'=>$data]);
}











// 
// Post functions
// 


function post_post()
{
	extract($_REQUEST);

	$post_id = data_post_create( $username, $title, $body );
	if($post_id){
		flash_set('New post created!');
		return redirectto('post', ['post_id'=>$post_id]);
	} else {
		flash_set('Invalid/missing values!', true);
		return get_new_post();
	}
}


function post_quick_post()
{
	extract($_REQUEST);

	if(data_post_create( $username, $title, false )){
		flash_set('New post created!');
		return redirectto('posts');
	} else {
		flash_set('Invalid/missing values!', true);
		return get_posts();
	}
}


function post_comment()
{
	extract($_REQUEST);

	if(data_comment_create( $username, $post_id, $body )){
		flash_set('Comment added!');
		return redirectto('post', ['post_id'=>$post_id, '__hash'=>'comments']);
	} else {
		flash_set('Invalid/missing values!', true);
		return get_post();
	}
}


// 
// Patch functions
// 


function patch_post()
{
	if(!_can_current_user_edit_post()) return get_404('Post not found.');

	extract($_REQUEST);

	if(data_post_update( $post_id, $title, $body )){
		flash_set("Post #$post_id updated!");
		return redirectto('post', ['post_id'=>$post_id]);
	} else {
		flash_set('Invalid/missing values!', true);
		return get_edit_post();
	}
}


function patch_comment()
{
	if(!_can_current_user_edit_comment()) return get_404('Comment not found.');

	extract($_REQUEST);

	if(data_comment_update( $post_id, $comment_id, $body )){
		flash_set('Comment updated!');
		return redirectto('post', ['post_id'=>$post_id]);
	} else {
		flash_set('Invalid/missing values!', true);
		return get_edit_comment();
	}
}


function patch_trash_post()
{
	if(!_can_current_user_edit_post()) return get_404('Post not found.');

	extract($_GET);

	flash_set(
		data_trash_post($post_id) ? "Post #$post_id moved to trash!" : "An error occurred!"
	);

	return redirectto('posts');
}


function patch_trash_comment()
{
	if(!_can_current_user_edit_comment()) return get_404('Comment not found.');

	extract($_GET);

	flash_set(
		data_trash_comment($post_id, $comment_id) ? "Comment#$comment_id in post#$post_id moved to trash!" : "An error occurred!"
	);

	return redirectto('post', ['post_id'=>$post_id]);
}




// Delete

function delete_post()
{
	extract($_GET);

	flash_set(
		data_delete_post($post_id) ? "Post #$post_id and comments deleted!" : "An error occurred!"
	);

	return redirectto('trash/posts');
}


function delete_comment()
{
	extract($_GET);

	flash_set(
		data_delete_comment($post_id, $comment_id) ? "Comment#$comment_id in post#$post_id deleted!" : "An error occurred!"
	);

	return redirectto('trash/comments');
}



// 
// Internal
// 

function _can_current_user_edit_post()
{
	extract($_REQUEST);

	$current_post_record = data_post_read($post_id, ['username', '__is_deleted']);
	return $current_post_record['username'] == $username;
}


function _can_current_user_edit_comment()
{
	extract($_REQUEST);
	
	$current_comment_record = data_comment_read($post_id, $comment_id, ['username', '__is_deleted']);
	return $current_comment_record['username'] == $username;
}
