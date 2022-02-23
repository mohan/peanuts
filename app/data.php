<?php
// Peanuts
// License: GPL

// 
// CSVDB
// 

$_csvdb_table_posts = [];
$_csvdb_table_comments = [];

function data_init()
{
	global $_csvdb_table_posts, $_csvdb_table_comments;

	$_csvdb_table_posts = [
		'data_dir' => CONFIG_DATA_DIR,
		'tablename' => 'posts.csv',
		'max_record_width' => 256,
		'columns' => [
			'username' => 'string',		// 20
			'title' => 'string',		// 128
			'body' => 'json',			// 20
			'meta' => 'json'			// 40
		],
		'validations_callback' => '_posts_table_validations',
		'auto_timestamps' => true		// 20
	];

	$_csvdb_table_comments = [
		'data_dir' => CONFIG_DATA_DIR,
		'tablename' => 'comments.csv',
		'max_record_width' => 160,
		'columns' => [
			'username' => 'string',		// 20
			'short_char' => 'string',	// 4 bytes
			'body' => 'json',			// 20
			'meta' => 'json'			// 40
		],
		'text_filename' => 'comments_body.text',
		'validations_callback' => '_comments_table_validations',
		'auto_timestamps' => true		// 20
	];
}


function _posts_table_validations($id, $values)
{
	if(!$values['username'] || !$values['title'] ||
		strlen($values['username']) > 20 ||
		strlen($values['title']) > 128
	) return false;

	return true;
}


function _comments_table_validations($id, $values)
{
	if(!$values['username'] || strlen($values['username']) > 20) return false;
	if( $values['short_char'] && $values['body'] ) return false;
	if( !$values['short_char'] && !$values['body'] ) return false;

	return true;
}









// 
// Posts
// 


// Data read

function data_post_list($page, $per_page)
{
	global $_csvdb_table_posts;
	return csvdb_list($_csvdb_table_posts, ['id', 'username', 'title', 'meta'], true, $page, $per_page);
}


function data_post_read($id, $columns=[])
{
	global $_csvdb_table_posts;
	$post = csvdb_read($_csvdb_table_posts, $id, $columns);
	
	if($post['body']) csvdb_text_fill_record($_csvdb_table_posts, ['body'], $post);

	return $post;
}


function data_post_pages_max($per_page)
{
	global $_csvdb_table_posts;
	return csvdb_last_id($_csvdb_table_posts) / $per_page;
}


// Data create

function data_post_create($username, $title, $body)
{
	global $_csvdb_table_posts;

	$values = [
		'username' => $username,
		'title' => $title,
		'body' => csvdb_text_create($_csvdb_table_posts, 'body', $body)
	];

	return csvdb_create($_csvdb_table_posts, $values);
}


function data_post_update($id, $title, $body)
{
	global $_csvdb_table_posts;

	$record = csvdb_read($_csvdb_table_posts, $id, ['body']);
	if(!$record) return false;

	$values = [
		'title' => $title,
		'body' => csvdb_text_update($_csvdb_table_posts, 'body', $record['body'], $body)
	];

	return csvdb_update($_csvdb_table_posts, $id, $values);
}












// 
// Comments
// 

function data_comment_list($post_id)
{
	global $_csvdb_table_comments;
	_data_set_comments_tablename($post_id);

	$records = csvdb_list($_csvdb_table_comments, [], true, 1, -1);
	csvdb_text_fill_records($_csvdb_table_comments, ['body'], $records);

	return $records;
}


function data_comment_read($post_id, $id, $columns=[])
{
	global $_csvdb_table_comments;
	_data_set_comments_tablename($post_id);
	$comment = csvdb_read($_csvdb_table_comments, $id, $columns);
	
	if($comment['body']) csvdb_text_fill_record($_csvdb_table_comments, ['body'], $comment);

	return $comment;
}


function data_comment_create($username, $post_id, $body)
{
	global $_csvdb_table_comments;
	_data_set_comments_tablename($post_id);

	$values = [
		'username' => $username,
		'body' => csvdb_text_create($_csvdb_table_comments, 'body', $body)
	];

	return csvdb_create($_csvdb_table_comments, $values);
}


function data_comment_update($post_id, $id, $body)
{
	global $_csvdb_table_comments;
	_data_set_comments_tablename($post_id);

	$record = csvdb_read($_csvdb_table_comments, $id, ['body']);
	if(!$record) return false;

	$values = [
		'body' => csvdb_text_update($_csvdb_table_comments, 'body', $record['body'], $body)
	];

	return csvdb_update($_csvdb_table_comments, $id, $values);
}





// 
// Internal
// 

function _data_set_comments_tablename($post_id)
{
	global $_csvdb_table_comments;
	$_csvdb_table_comments['tablename'] = "post-$post_id-comments.csv";
}
