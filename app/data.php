<?php
// Peanuts
// License: GPL

// 
// CSVDB
// 


function data_table_posts()
{
	static $t = [
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
		'auto_timestamps' => true,		// 20
		// 'log' => true
	];

	// meta
	// comments, stars, views
	// { c: 10, s: 10, v: 10 }

	return $t;
}


function data_table_comments($post_id)
{
	static $t = [
		'data_dir' => CONFIG_DATA_DIR,
		'tablename' => '',
		'max_record_width' => 160,
		'columns' => [
			'username' => 'string',		// 20
			'short_char' => 'string',	// 4 bytes
			'body' => 'json',			// 20
			'meta' => 'json'			// 40
		],
		'text_filename' => 'comments_body.text',
		'validations_callback' => '_comments_table_validations',
		'auto_timestamps' => true,		// 20
		// 'log' => true
	];

	$t['tablename'] = "post-$post_id-comments.csv";

	return $t;
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
	$t = data_table_posts();
	return csvdb_list($t, ['id', 'username', 'title', 'meta', 'updated_at'], true, $page, $per_page);
}


function data_trash_post_list()
{
	$t = data_table_posts();
	$posts = csvdb_list($t, ['id', 'username', 'title', 'created_at', 'updated_at', '__is_deleted'], false, 1, -1, function($r){
		return $r['__is_deleted'] === 1;
	}, function($r){
		unset($r['__is_deleted']);
		return $r;
	});

	return $posts;
}


function data_post_read($id, $columns=[])
{
	$t = data_table_posts();
	$post = csvdb_read($t, $id, $columns);
	
	if($post['body']) csvdb_text_fill_record($t, ['body'], $post);

	return $post;
}


function data_post_pages_max($per_page)
{
	$t = data_table_posts();
	return csvdb_last_id($t) / $per_page;
}


function data_post_hashtags($hashtag)
{
	$t = data_table_posts();
	$posts = csvdb_list($t, ['id', 'username', 'title', 'meta', 'updated_at']);

	$hashtags = [];
	foreach ($posts as $key => $post) {
		if(preg_match_all("/#([a-zA-Z0-9-_]+)/", $post['title'], $matches) && sizeof($matches[1]) > 0){
			$hashtags = array_merge($hashtags, $matches[1]);
		}

		if($hashtag) {
			if(strpos($post['title'], "#$hashtag") === false) unset($posts[$key]);
		}
	}

	$hashtags = array_unique($hashtags);

	if($hashtag){
		return ['hashtags' => $hashtags, 'hashtag' => $hashtag, 'posts' => $posts];
	} else {
		return ['hashtags' => $hashtags];
	}
}


// Data create

function data_post_create($username, $title, $body)
{
	$t = data_table_posts();
	$values = [
		'username' => $username,
		'title' => $title,
		'body' => csvdb_text_create($t, 'body', $body)
	];

	return csvdb_create($t, $values);
}


function data_post_update($id, $title=false, $body=false)
{
	$t = data_table_posts();
	$record = csvdb_read($t, $id, ['body', '__is_deleted']);
	if(!$record) return false;

	$values = [];
	if($title && $body){
		$values = [
			'title' => $title,
			'body' => csvdb_text_update($t, 'body', $record['body'], $body)
		];
	}

	return csvdb_update($t, $id, $values);
}


// Data delete

function data_trash_post($id)
{
	$t = data_table_posts();
	return csvdb_delete($t, $id, true);
}

function data_delete_post($id)
{
	$t = data_table_posts();
	$return_val = csvdb_delete($t, $id);

	if($return_val){
		$comments_filepath = _csvdb_is_valid_config(data_table_comments($id));
		if($comments_filepath) file_put_contents($comments_filepath, '');
	}

	return $return_val;
}



function data_post_meta_count_increment_decrement($post_id, $type, $action)
{
	$t = data_table_posts();

	switch ($action) {
		case '+': $factor =  1; break;
		case '-': $factor = -1; break;
		default: return;
	}

	$post = csvdb_read($t, $post_id, ['meta']);
	if(!$post) return;

	// Don't change updated at for views
	// if($type == 'v') $post['updated_at'] = false;

	$post['meta'][$type] = $post['meta'][$type] + $factor;
	csvdb_update($t, $post_id, $post);
}






// 
// Comments
// 

function data_comment_list($post_id)
{
	$t = data_table_comments($post_id);
	$records = csvdb_list($t, [], true, 1, -1);
	
	// Remove no text records, used for short_char/metadata.
	foreach ($records as $key => $record) {
		if(!$record['body']) unset($records[$key]);
	}

	csvdb_text_fill_records($t, ['body'], $records);

	return $records;
}


function data_comment_read($post_id, $id, $columns=[])
{
	$t = data_table_comments($post_id);
	$comment = csvdb_read($t, $id, $columns);
	$comment['post_id'] = $post_id;
	
	if($comment['body']) csvdb_text_fill_record($t, ['body'], $comment);

	return $comment;
}


function data_comment_create($username, $post_id, $body)
{
	$t = data_table_comments($post_id);
	$values = [
		'username' => $username,
		'body' => csvdb_text_create($t, 'body', $body)
	];

	if($body) data_post_meta_count_increment_decrement($post_id, 'c', '+');

	return csvdb_create($t, $values);
}


function data_comment_update($post_id, $id, $body=false)
{
	$t = data_table_comments($post_id);
	$record = csvdb_read($t, $id, ['body', '__is_deleted']);
	if(!$record) return false;

	$values = [];
	if($body){
		$values = [
			'body' => csvdb_text_update($t, 'body', $record['body'], $body)
		];
	}

	return csvdb_update($t, $id, $values);
}

function data_comment_pages_max($post_id, $per_page)
{
	$t = data_table_comments($post_id);
	return csvdb_last_id($t) / $per_page;
}


function data_trash_comments_list()
{
	$tp = data_table_posts();

	$posts_with_trashed_comments = csvdb_list($tp, ['id', 'title', 'meta'], false, 1, -1, function($r){
		return $r['meta']['t_comments'] === true;
	});

	$trash_comments = [];
	foreach ($posts_with_trashed_comments as $post) {
		$post_id = $post['id'];
		$t = data_table_comments($post_id);

		$comments = csvdb_list($t, ['id', 'username', 'body', 'created_at', 'updated_at', '__is_deleted'], false, 1, -1, function($r){
			return $r['__is_deleted'] === 1;
		}, function($r) use ($post){
			$r['post'] = $post;
			return $r;
		});

		csvdb_text_fill_records($t, ['body'], $comments);

		$trash_comments = array_merge($trash_comments, $comments);
	}

	return $trash_comments;
}


// Delete

function data_trash_comment($post_id, $id)
{
	$tp = data_table_posts();
	$t = data_table_comments($post_id);

	// Has trash comments
	csvdb_update($tp, $post_id, ['meta'=>['t_comments'=>true], 'updated_at'=>false]);

	return csvdb_delete($t, $id, true);
}


function data_delete_comment($post_id, $id)
{
	$tp = data_table_posts();
	$t = data_table_comments($post_id);

	// Has trash comments
	csvdb_update($tp, $post_id, ['meta'=>['t_comments'=>false], 'updated_at'=>false]);

	data_post_meta_count_increment_decrement($post_id, 'c', '-');

	return csvdb_delete($t, $id);
}
