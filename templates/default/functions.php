<?php

function pagetitle($uri)
{
	switch ($uri) {
		case 'login.php':
			return 'Login - ' . CONFIG_APP_NAME;

		case 'posts.php':
			$title = 'Posts';
			break;

		case 'new-post.php':
			$title = 'New Post';
			break;

		case '404.php':
			return '404 - ' . CONFIG_APP_NAME;
			break;
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
