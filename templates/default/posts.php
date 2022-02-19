<?php
global $csvdb_tables;
$posts = csvdb_list($csvdb_tables['posts']);
$sample_posts_table = [
	"data_dir" => './',
	"tablename" => 'sample_posts.csv',
	"max_record_width" => 128,
	"columns" => [
			"username"=>"string",
			"title"=>"string",
			"body"=>"json"
	],
	"auto_timestamps" => true
];

$sample_posts = csvdb_list($sample_posts_table);
$posts = array_merge($posts, $sample_posts);
?>

<div id='posts'>
	<?php foreach($posts as $r_id => $post): ?>
		<div id='post-<?php echo $r_id; ?>' class='post'>
			<h3><?php echo $post['title'];?></h3>
			<p><?php echo csvdb_text_read($csvdb_tables['posts'], 'body', $post['body']); ?></p>
			<h4><?php echo CONFIG_USERS[$post['username']];?></h4>
			<p><?php echo date('F j, Y, g:i a', $post['created_at']);?></p>
		</div>
	<?php endforeach; ?>
</div>
