<?php
// Peanuts
// License: GPL

// 
// CSVDB
// 

$_csvdb_tables = [];

function data_init()
{
	global $_csvdb_tables;

	$_csvdb_tables['posts'] = [
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

	csvdb_create_table($_csvdb_tables['posts']);
}


function csvdb_posts_table_validations($r_id, $values)
{
	if(!$values['username'] || !$values['title'] ||
		strlen($values['username']) > 20 ||
		strlen($values['title']) > 128
	) return false;

	return true;
}


// Data read

function data_post_list($page, $per_page)
{
	global $_csvdb_tables;
	return csvdb_list($_csvdb_tables['posts'], $page, $per_page, true);
}


function data_post($id)
{
	global $_csvdb_tables;
	$post = csvdb_read($_csvdb_tables['posts'], $id);

	if($post == -1) $post = false;
	if($post) $post['body'] = csvdb_text_read($_csvdb_tables['posts'], 'body', $post['body']);

	return $post;
}


function data_post_pages_max($per_page)
{
	global $_csvdb_tables;
	return csvdb_last_r_id($_csvdb_tables['posts']) / $per_page;
}


// Data create

function data_post_create()
{
	global $_csvdb_tables;

	$body_ref = $_POST['body'] ? csvdb_text_create($_csvdb_tables['posts'], 'body', $_POST['body']) : false;

	$values = [
		username => $_REQUEST['APP']['username'],
		title => $_POST['title'],
		body => $body_ref
	];

	return csvdb_create($_csvdb_tables['posts'], $values);
}
