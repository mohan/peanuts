<?php
// Peanuts
// License: GPL

// 
// CSVDB
// 

$csvdb_tables = [];

function data_init()
{
	global $csvdb_tables;

	$csvdb_tables['posts'] = [
		"data_dir" => CONFIG_DATA_DIR,
		"tablename" => 'posts.csv',
		"max_record_width" => 256,
		"columns" => [
			"username"=>"string",
			"title"=>"string",
			"body"=>"json"
		],
		"validations_callback" => "csvdb_posts_table_validations",
		"auto_timestamps" => true,
	];

	csvdb_create_table($csvdb_tables['posts']);
}


function csvdb_posts_table_validations($r_id, $values, $t)
{
	if(!$values['username'] || !$values['title'] ||
		strlen($values['username']) > 20 ||
		strlen($values['title']) > 128
	) return false;

	return true;
}


// Data read

function data_post_list($page=1)
{
	global $csvdb_tables;
	return csvdb_list($csvdb_tables['posts'], $page, 30, true);
}


// Data create

function data_post_create()
{
	global $csvdb_tables;

	$values = [
		username => $_REQUEST['PEANUTS']['username'],
		title => $_POST['title'],
		body => $_POST['body']
	];

	return csvdb_create($csvdb_tables['posts'], $values);
}
