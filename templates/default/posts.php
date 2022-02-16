<?php
global $csvdb_tables;
$posts = csvdb_list_records($csvdb_tables['posts']);
?>

<div id='posts'>
	<?php foreach($posts as $r_id => $post): ?>
		<div id='post-<?php echo $r_id; ?>' class='post'>
			<h3><?php echo $post['title'];?></h3>
			<h4><?php echo CONFIG_USERS[$post['username']];?></h4>
			<p><?php echo date('F j, Y, g:i a', $post['created_at']);?></p>
		</div>
	<?php endforeach; ?>
</div>
