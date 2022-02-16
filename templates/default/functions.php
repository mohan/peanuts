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
	}

	return $title . ' - ' . CONFIG_TEAM_NAME;
}
