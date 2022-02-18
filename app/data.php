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
		"max_record_width" => 100,
		"columns" => [
			"username"=>"string",
			"title"=>"string",
			"body"=>"json"
		],
		"validations_callback" => "csvdb_posts_table_validations",
		"auto_timestamps" => true,
	];
}

function csvdb_posts_table_validations($r_id, $values, $t)
{
	if(!$values['username'] || !$values['title'] || !$values['body'] ||
		strlen($values['username']) > 20 ||
		strlen($values['title']) > 50
	) return false;

	return true;
}
