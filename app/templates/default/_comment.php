<div id='comment-<?= $comment['id']; ?>' class='comment clear'>
	<div class='user-container'>
		<?php tag(user_initial($comment['username']), ['class'=>'user-icon'], 'h4'); ?>
	</div>
	<div class='header-container'>
		<?php tag($comment['body'], ['class'=>'markdown'], 'pre'); ?>
		<p class='small text-muted'>
			<?= CONFIG_USERS[$comment['username']]; ?> /
			<?= date('M j, Y, g:i a', $comment['created_at']);?>
			<?php if( $comment['username'] == $_REQUEST['username'] ) {
					echo ' / ';
					linkto('edit-comment', 'Edit', ['id'=>$comment['id'], 'post_id'=>$post_id], ['class'=>'text-muted']);
				}
			?>
		</p>
	</div>
</div>
