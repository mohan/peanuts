<div id='post'>
	<?= tag($_REQUEST['flash'], ['id'=>'flash', 'class'=>'text-center']); ?>
	<?php if($post['body']): ?>
		<div id='post-<?= $post['id']; ?>' class='border-bottom p-b m-b'>
			<?= tag(user_initial($post['username']), ['class'=>'user-icon'], 'h4'); ?>
			<h3 class='title'>
				<a href='<?= urltoget('post', [id=>$post['id']]); ?>' class='d-block'>
					<?= tag($post['title'], ['class'=>'markdown'], 'pre') ?>
				</a>
			</h3>

			<div class='markdown'><?= render_markdown($post['body'], true); ?></div>

			<?php render_partial('_post-meta.php', ['post'=>$post, 'show_edit_link'=>true]); ?>
		</div>
	<?php else: ?>
		<div class='border-bottom p-b m-b'>
			<?php render_partial('_post.php', ['post'=>$post, 'show_edit_link'=>true]); ?>
		</div>
	<?php endif; ?>

	<div id='comments'>
		<h3>Comments</h3>
		<div class='comment'>
			<div class='user-container'>
				<?= tag(user_initial($_REQUEST['username']), ['class'=>'user-icon', 'style'=>'margin-top:30px;'], 'h4'); ?>
			</div>
			<?= formto('create-comment', ['post_id' => $post['id']], ['class'=>'header-container']); ?>
				<label for='comment-body-editor' class='d-block text-muted small'>
					Add Comment
					(<?= linkto('page', 'Markdown', ['slug'=>'markdown'], ['class'=>'text-muted small', 'target'=>'app-page']); ?>)
				</label>
				<?= tag($body, ['id'=>'comment-body-editor', 'name'=>'body', 'class'=>'editor', 'style'=>'height:100px'], 'textarea'); ?>
				<input type='submit' value='Submit' class='btn btn-primary' />
			</form>
		</div>
		<?php foreach($comments as $comment): ?>
			<?php render_partial('_comment.php', ['comment'=>$comment, 'post_id'=>$post['id']]); ?>
		<?php endforeach; ?>
	</div>
</div>
