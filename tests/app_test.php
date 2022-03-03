<?php

define('APP_ENV_IS_TEST', true);
define('ROOT_DIR', __DIR__ . '/../');
define('APP_DIR', ROOT_DIR . '/app/');

define('ENABLE_WEB_TEST_RESULTS_INTERFACE', true);

require ROOT_DIR . '/lib/test-helpers.php';
require APP_DIR . '/init.php';



call_tests([
	'get_login',
	'post_login',
	'post_logout',

	'get_root',
	'get_new_post',
	'get_posts',
	'get_post',
	'get_edit_post',
	'get_edit_comment',
	'get_hashtags',
	'get_page',

	'post_quick_post',
	'post_post',
	'post_comment',

	'patch_post',
	'patch_comment',

	'patch_trash_post',
	'patch_trash_comment',

	'get_trash_posts',
	'get_trash_comments'

], __FILE__);



function test_get_login()
{
	$url = urltoget('login');
	$response = do_get($url);
	t("Login page renders", sizeof($response['headers']) == 0 &&
					strpos($response['body'], "<title>Login") !== false
	);

	$response = do_get($url, ['auth'=>_auth()]);
	t("Login page renders with existing auth?", sizeof($response['headers']) == 0 &&
					strpos($response['body'], "<title>Login") !== false
	);
}


function test_post_login()
{
	$url = urltopost('login');
	$response = do_post($url, ['post_username' => 'user1', 'post_team_password'=>'0123456789']);
	// TODO: check cookie
	t("post to login", is_redirect(urltoget('posts'), $response));
}


function test_post_logout()
{
	$url = urltopost('logout');
	$response = do_post($url);
	// TODO: check cookie
	t("logout", is_redirect(urltoget('login'), $response));
}



function test_get_root()
{
	$response = _test_get_user_page($url);
	t("gets posts", is_redirect(urltoget('posts'), $response));
}


function test_get_new_post()
{
	$url = urltoget('new-post');
	$response = _test_get_user_page($url);
	t("gets new post form", _is_user_page($response, formto('post')));
}


function test_get_posts()
{
	for($i=0; $i<=30; $i++) _create_new_post();

	$url = urltoget('posts');
	$response = _test_get_user_page($url);
	t("gets posts", _is_user_page($response, [
						formto('quick-post'),
						"<div class='post-panel clear'>"
					]) &&
					substr_count($response['body'], "<div class='post-panel clear'>") == 30
				);
}


function test_get_post()
{
	extract(_create_new_post(), EXTR_PREFIX_ALL, 'p');

	$url = urltoget('post', ['post_id'=>$p_id]);
	$response = _test_get_user_page($url);
	t("gets post post_id $p_id", _is_user_page($response, html_markdown($p_body)));
}


function test_get_edit_post()
{
	extract(_create_new_post(), EXTR_PREFIX_ALL, 'p');

	$url = urltoget('edit-post', ['post_id'=>$p_id]);
	$response = _test_get_user_page($url);
	t("edit page for post_id $p_id", _is_user_page($response, $p_body));
}


function test_get_edit_comment()
{
	extract(_create_new_post(), EXTR_PREFIX_ALL, 'p');
	extract(_create_new_comment($p_id), EXTR_PREFIX_ALL, 'c');

	$url = urltoget('edit-comment', ['post_id'=>$p_id, 'comment_id'=>$c_id]);
	$response = _test_get_user_page($url);
	t("edit page for comment_id $c_id", _is_user_page($response, $c_body));
}


function test_get_hashtags()
{
	$post = _create_new_post('Post with hashtag #zxcv.');

	$url = urltoget('hashtags');
	$response = _test_get_user_page($url);
	t("Hashtags", _is_user_page($response, '#zxcv</a>'));
}


function test_get_page()
{
	$url = urltoget('page', ['slug'=>'readme']);
	$response = _test_get_user_page($url);
	t("Page", _is_user_page($response, 'License: GPL (Free as in free peanuts.)'));
}


function test_get_trash_posts()
{
	$posts = data_trash_post_list();
	$url = urltoget('trash/posts');
	$response = _test_get_user_page($url);
	t("Gets list of trashed posts", _is_user_page($response, (current($posts))['title']));
}


function test_get_trash_comments()
{
	$comments = data_trash_comments_list();
	$url = urltoget('trash/comments');
	$response = _test_get_user_page($url);
	t("Gets list of trashed comments", _is_user_page($response, (current($comments))['body']));
}


// 
// Post requests
// 


function test_post_quick_post()
{
	$url = urltopost('quick-post');
	$response = _test_post_user_page($url, ['title'=>'This is a quick post ' . time()]);
	$post_id = data_post_pages_max(1);
	t("creates a quick post $post_id", is_redirect(urltoget('posts'), $response) && is_flash("New post created!", $response));
}


function test_post_post()
{
	$url = urltopost('post');
	$response = _test_post_user_page($url, ['title'=>'Test post title', 'body'=>'Post body']);
	$post_id = data_post_pages_max(1);
	t("creates a new post $post_id", 
		is_redirect(
			urltoget('post', ['post_id' => $post_id]),
			$response
		)
	);
}


function test_patch_post()
{
	extract(_create_new_post(), EXTR_PREFIX_ALL, 'p');

	$url = urltopost('post', ['__method'=>'patch', 'post_id' => $p_id]);
	$response = _test_post_user_page($url, ['title' => 'Patched title', 'body' => 'patched text.']);
	t("Patches a post $p_id", 
		is_redirect(
			urltoget('post', ['post_id' => $p_id]),
			$response
		)
	);
}


function test_post_comment()
{
	extract(_create_new_post(), EXTR_PREFIX_ALL, 'p');

	$url = urltopost('comment', ['post_id'=>$p_id]);
	$response = _test_post_user_page($url, ['body'=>'Test comment body']);
	$comment_id = data_comment_pages_max($p_id, 1);
	t("creates a new comment $comment_id", 
		is_redirect(
			urltoget('post', ['post_id' => $p_id, '__hash' => 'comments']),
			$response
		)
	);
}


function test_patch_comment()
{
	extract(_create_new_post(), EXTR_PREFIX_ALL, 'p');
	extract(_create_new_comment($p_id), EXTR_PREFIX_ALL, 'c');

	$url = urltopost('comment', ['__method'=>'patch', 'post_id'=>$p_id, 'comment_id'=>$c_id]);
	$response = _test_post_user_page($url, ['body' => 'patched text...']);
	t("patches comment $c_id post $p_id", is_redirect( urltoget('post', ['post_id' => $p_id]), $response ) );
}


function test_patch_trash_post()
{
	extract(_create_new_post());

	$url = urltopost('trash/post', ['__method'=>'patch', 'post_id'=>$id]);
	$response = _test_post_user_page($url, []);
	t("Trash post $id", is_redirect( urltoget('posts'), $response ) && is_flash("Post #$id moved to trash!", $response) );
}


function test_patch_trash_comment()
{
	extract(_create_new_post(), EXTR_PREFIX_ALL, 'p');
	extract(_create_new_comment($p_id), EXTR_PREFIX_ALL, 'c');

	$url = urltopost('trash/comment', ['__method'=>'patch', 'post_id'=>$p_id, 'comment_id'=>$c_id]);
	$response = _test_post_user_page($url, []);

	t("Trash post #$p_id - comment $c_id",
			is_redirect( urltoget('post', ['post_id'=>$p_id]), $response ) &&
			is_flash("Comment#$c_id in post#$p_id moved to trash!", $response)
		);
}



// 
// Internal functions
// 

function _auth()
{
	$value = base64_encode("user1%" . _secure_cookie_authenticity_token('auth', 'user1', time()));
	return $value;
}


function _is_user_page($response, $html_in_response_body=false)
{
	$result = is_not_redirect($response) &&
				(
					strpos($response['body'],
						tag(CONFIG_USERS[$_REQUEST['username']], ['id'=>'navbar-username'], 'span')
					) !== false
				);
	if(!$result) {
		echo "is_not_redirect failed\n";
		return false;
	}

	if($html_in_response_body !== false) {
		if(is_array($html_in_response_body)){
			foreach ($html_in_response_body as $html) {
				if(strpos($response['body'], $html) === false) {
					echo "html_in_response_body failed - " . htmlentities($html) . "\n";
					return false;
				}
			}
		} else {
			if(strpos($response['body'], $html_in_response_body) === false){
				echo "html_in_response_body failed - " . htmlentities($html_in_response_body) . "\n";
				return false;
			}
		}
	}

	return true;
}


function _test_get_user_page($url)
{
	$response = do_get($url);
	t("redirects to login if not logged-in", is_redirect(urltoget('login'), $response));

	return do_get($url, ['auth'=>_auth()]);
}


function _test_post_user_page($url, $params)
{
	$response = do_post($url, $params);
	t("redirects to login if not logged-in", is_redirect(urltoget('login'), $response));

	return do_post($url, $params,  ['auth'=>_auth()]);
}


function _create_new_post($title='', $body='')
{
	$url = urltopost('post');
	$response = do_post($url, ['title' => $title ? $title : 'Post #' . rand(), 'body' => $body ? $body : 'Post body #' . rand()], ['auth'=>_auth()]);
	$post_id = data_post_pages_max(1);
	return data_post_read($post_id);
}


function _create_new_comment($post_id)
{
	$url = urltopost('comment', ['post_id'=>$post_id]);
	$response = do_post($url, ['body' => 'Comment body #' . rand()], ['auth'=>_auth()]);
	$comment_id = data_comment_pages_max($post_id, 1);
	return data_comment_read($post_id, $comment_id);
}
