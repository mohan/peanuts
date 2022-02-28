<?php

define('APP_ENV_IS_TEST', true);
define('ROOT_DIR', __DIR__ . '/../');

require ROOT_DIR . '/lib/test-helpers.php';
require ROOT_DIR . '/index.php';

define(LOGIN_URL, urltoget('login'));
$test_post_data = ['title'=>'test-post-1234 #zxcv', 'body' => 'test-post-body-1234'];
$test_post_id = 0;
$test_comment_data = ['body' => 'test-comment-body-1234'];
$test_comment_id = 0;


call_tests([
	'get_login',
	'post_login',
	'post_logout',

	'post_quick_post',
	'post_post',
	'post_comment',

	'get_root',
	'get_new_post',
	'get_posts',
	'get_post',
	'get_edit_post',
	'get_edit_comment',
	'get_hashtags',
	'get_page',

	'patch_post',
	'patch_comment',

	// 'delete_post',
	// 'delete_comment'
]);



function test_get_login()
{
	$url = urltoget('login');
	$response = do_get($url);
	t("Login page renders", sizeof($response['headers']) == 0 &&
					strpos($response['body'], "<title>Login") !== false
	);

	$response = do_get($url, ['auth_username'=>_auth_username()]);
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
	$url = '';
	$response = do_get($url);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_get($url, ['auth_username'=>_auth_username()]);
	t("gets posts", is_redirect(urltoget('posts'), $response));
}


function test_get_new_post()
{
	$url = urltoget('new-post');
	$response = do_get($url);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_get($url, ['auth_username'=>_auth_username()]);
	t("gets new post form", _is_user_page($response, formto('post')));
}


function test_get_posts()
{
	$url = urltoget('posts');
	$response = do_get($url);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_get($url, ['auth_username'=>_auth_username()]);
	t("gets posts", _is_user_page($response, formto('quick-post')));
}


function test_get_post()
{
	global $test_post_data;
	global $test_post_id;

	$url = urltoget('post', ['post_id'=>$test_post_id]);
	$response = do_get($url);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_get($url, ['auth_username'=>_auth_username()]);
	t("gets post post_id $test_post_id", _is_user_page($response, html_markdown($test_post_data['body'])));
}


function test_get_edit_post()
{
	global $test_post_data;
	global $test_post_id;

	$url = urltoget('edit-post', ['post_id'=>$test_post_id]);
	$response = do_get($url);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_get($url, ['auth_username'=>_auth_username()]);
	t("edit page for post_id $test_post_id", _is_user_page($response, $test_post_data['body']));
}


function test_get_edit_comment()
{
	global $test_comment_data;
	global $test_post_id;
	global $test_comment_id;

	$url = urltoget('edit-comment', ['post_id'=>$test_post_id, 'comment_id'=>$test_comment_id]);
	$response = do_get($url);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_get($url, ['auth_username'=>_auth_username()]);
	t("edit page for comment_id $test_comment_id", _is_user_page($response, $test_comment_data['body']));
}


function test_get_hashtags()
{
	$url = urltoget('hashtags');
	$response = do_get($url);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_get($url, ['auth_username'=>_auth_username()]);
	t("Hashtags", _is_user_page($response, '#zxcv'));
}


function test_get_page()
{
	$url = urltoget('page', ['slug'=>'readme']);
	$response = do_get($url);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_get($url, ['auth_username'=>_auth_username()]);
	t("Page", _is_user_page($response, 'License: GPL (Free as in free peanuts.)'));
}


// 
// Post requests
// 


function test_post_quick_post()
{
	global $test_post_data;
	global $test_post_id;

	$url = urltopost('quick-post');
	$response = do_post($url, $test_post_data);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_post($url, $test_post_data, ['auth_username'=>_auth_username()]);
	$test_post_id = data_post_pages_max(1);
	t("creates a quick post $test_post_id", is_redirect( urltoget('posts'), $response));
}


function test_post_post()
{
	global $test_post_data;
	global $test_post_id;

	$url = urltopost('post');
	$response = do_post($url, $test_post_data);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_post($url, $test_post_data, ['auth_username'=>_auth_username()]);
	$test_post_id = data_post_pages_max(1);
	t("creates a new post $test_post_id", 
		is_redirect(
			urltoget('post', ['post_id' => $test_post_id]),
			$response
		)
	);
}


function test_patch_post()
{
	global $test_post_data;
	global $test_post_id;

	$url = urltopost('post', ['__method'=>'patch', 'post_id' => $test_post_id]);
	$response = do_post($url, $test_post_data);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_post($url, $test_post_data, ['auth_username'=>_auth_username()]);
	t("Patches a post $test_post_id", 
		is_redirect(
			urltoget('post', ['post_id' => $test_post_id]),
			$response
		)
	);
}


function test_post_comment()
{
	global $test_comment_data;
	global $test_post_id;
	global $test_comment_id;

	$url = urltopost('comment', ['post_id'=>$test_post_id]);
	$response = do_post($url, $test_comment_data);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_post($url, $test_comment_data, ['auth_username'=>_auth_username()]);
	$test_comment_id = data_comment_pages_max($test_post_id, 1);
	t("creates a new comment $test_comment_id", 
		is_redirect(
			urltoget('post', ['post_id' => $test_post_id, '__hash' => 'comments']),
			$response
		)
	);
}


function test_patch_comment()
{
	global $test_comment_data;
	global $test_post_id;
	global $test_comment_id;

	$url = urltopost('comment', ['__method'=>'patch', 'post_id'=>$test_post_id, 'comment_id'=>$test_comment_id]);
	$response = do_post($url, $test_comment_data);
	t("redirects if not logged-in", is_redirect(LOGIN_URL, $response));

	$response = do_post($url, $test_comment_data, ['auth_username'=>_auth_username()]);
	t("patches comment $test_comment_id post $test_post_id", is_redirect( urltoget('post', ['post_id' => $test_post_id]), $response ) );
}


// 
// Internal functions
// 

function _auth_username()
{
	return "user1%" . _secure_cookie_authenticity_token('auth_username', 'user1', time());
}


function _is_user_page($response, $html_in_response_body=false)
{
	$result = is_not_redirect($response) &&
				(
					strpos($response['body'],
						tag(CONFIG_USERS[$_REQUEST['username']], ['id'=>'navbar-username'], 'span')
					) !== false
				);

	if($html_in_response_body) {
		$result = $result && strpos($response['body'], $html_in_response_body) !== false;
	}

	return $result;
}
