<?php
global $csvdb_tables;
$posts = csvdb_list($csvdb_tables['posts']);
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
