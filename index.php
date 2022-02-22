<?php
// Peanuts
// License: GPL

require_once './lib/csvdb.php';
require_once './lib/helpers.php';
require_once './app/data.php';
require_once './app/functions.php';

// filters
filter_set_config('./config.ini');
filter_permitted_params(
	[ 'uri', 'post-uri', 'id', 'page' ],
	[ 'username', 'team_password', 'title', 'body', 'id' ],
	[ 'id' => 'int', 'page' => 'int' ],
	[ 'id' => 'int' ]
);
filter_set_username();
filter_set_flash();

// Template functions
require_once './app/templates/' . CONFIG_TEMPLATE . '/functions.php';

// data
data_init();

// Routes
filter_routes(
	[ 'login', 'new-post', 'posts', 'post' ],
	[ 'login', 'logout', 'create-quick-post', 'create-post' ]
);
