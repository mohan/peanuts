<div id='post'>
	<div id='post-<?= $post['id']; ?>' class='border-bottom p-b m-b'>
		<?php tag(user_initial($post['username']), ['class'=>'user-icon'], 'h4'); ?>
		<h3 class='title'>
			<a href='<?= urltoget('post', [id=>$post['id']]); ?>' class='d-block'>
				<?= tag($post['title'], ['class'=>'markdown'], 'pre') ?>
			</a>
		</h3>

		<?php tag($post['body'], ['class'=>'markdown'], 'pre'); ?>

		<p class='small text-muted'>
			<?= CONFIG_USERS[$post['username']]; ?> /
			<?= date('M j, Y, g:i a', $post['created_at']);?>
			<?php if( $post['username'] == $_REQUEST['username'] ) {
					echo ' / ';
					linkto('edit-post', 'Edit', ['id'=>$post['id']]);
				}
			?>
		</p>
	</div>

	<div id='comments'>
		<h3>Comments</h3>
		<?= formto('create-comment', ['id' => $id], ['class'=>'header-container']); ?>
			<label for='comment-body-editor' class='d-block text-muted small'>Add Comment</label>
			<?php tag($body, ['id'=>'comment-body-editor', 'name'=>'body', 'class'=>'editor', 'style'=>'height:100px'], 'textarea'); ?>
			<input type='submit' value='Submit' class='btn btn-primary' />
		</form>
		<?php foreach($comments as $comment): ?>
			<?php render_partial('_comment.php', ['comment'=>$comment, 'post_id'=>$id]); ?>
		<?php endforeach; ?>
	</div>
</div>
