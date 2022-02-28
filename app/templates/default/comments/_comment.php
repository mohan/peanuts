<div id='comment-<?= $comment['id']; ?>' class='comment clear'>
	<div class='user-container'>
		<?= html_user_icon($comment['username']); ?>
	</div>
	<div class='header-container' style='padding-top:20px;'>
		<?= html_markdown($comment['body']); ?>
		<p class='small text-muted'>
			<?= CONFIG_USERS[$comment['username']]; ?> /
			<?= date('M j, Y, g:i a', $comment['created_at']);?>
			<?php if( $comment['username'] == $_REQUEST['username'] ) {
					echo ' / ';
					echo link_editcomment('Edit', $post, $comment, ['class'=>'text-muted']);
				}
			?>
		</p>
	</div>
</div>
