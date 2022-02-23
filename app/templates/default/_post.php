<div id='post-<?= $post['id']; ?>' class='post clear'>
	<div class='user-container'>
		<?php tag(user_initial($post['username']), ['class'=>'user-icon'], 'h4'); ?>
	</div>
	<div class='header-container'>
		<h3>
			<a href='<?= urltoget('post', [id=>$post['id']]); ?>' class='d-block'>
				<?= nl2br(htmlentities($post['title'])); ?>
			</a>
		</h3>
		<p class='small text-muted'>
			<?= CONFIG_USERS[$post['username']]; ?> /
			<?= date('M j, Y, g:i a', $post['created_at']);?>
		</p>
	</div>
</div>
