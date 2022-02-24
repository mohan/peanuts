<?php

function pagetitle($uri, $args)
{
	switch ($uri) {
		case 'login.php':
			return 'Login - ' . CONFIG_APP_TITLE;

		case 'post.php':
			$title = substr($args['post']['title'], 0, 20); break;

		case 'hashtags.php':
			$title = 'Hashtags'; break;

		case 'posts.php':
			$title = 'Posts'; break;

		case 'post-editor.php':
			$title = $args['id'] ? 'Edit Post #' . $args['id'] : 'New Post'; break;

		case 'comment-editor.php':
			$title = 'Edit Comment #' . $args['id']; break;

		case '404.php':
			return '404 - ' . CONFIG_APP_TITLE; break;
	}

	return $title . ' - ' . CONFIG_TEAM_NAME;
}


function user_initial($username)
{
	$parts = explode(' ', CONFIG_USERS[$username]);
	$initials = '';
	foreach ($parts as $part) {
		if(preg_match_all("/^[a-zA-Z0-9]{1,1}/", $part[0]) == 1) $initials .= $part[0];
	}

	return strtoupper($initials);
}
