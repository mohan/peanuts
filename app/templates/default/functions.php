<?php

function user_initial($username)
{
	preg_match_all("/\b([A-Za-z0-9])/", CONFIG_USERS[$username], $matches);
	return strtoupper(join($matches[1]));
}
