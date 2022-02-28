<div id='post'>
	<?= html_flash(); ?>
	<?php if($post['body']): ?>
		<div id='post-<?= $post['id']; ?>' class='border-bottom p-b m-b'>
			<?= html_user_icon($post['username']) ?>
			<h3 class='title'>
				<a href='<?= url_getpost($post); ?>' class='d-block'>
					<?= tag($post['title'], ['class'=>'markdown'], 'pre') ?>
				</a>
			</h3>

			<?= html_markdown($post['body']); ?>

			<?php render_partial('posts/_post-meta.php', ['post'=>$post, 'show_edit_link'=>true]); ?>
		</div>
	<?php else: ?>
		<div class='border-bottom p-b m-b'>
			<?php render_partial('posts/_post.php', ['post'=>$post, 'show_edit_link'=>true]); ?>
		</div>
	<?php endif; ?>

	<div id='comments'>
		<h3>Comments</h3>
		<div class='comment'>
			<div class='user-container' style='margin-top:10px;'>
				<?= html_user_icon($_REQUEST['username']) ?>
			</div>
			<?= formto('comment', ['post_id' => $post['id']], ['class'=>'header-container']); ?>
				<label for='comment-body-editor' class='d-block text-muted small'>
					Add Comment
					(<?= linkto('page', 'Markdown', ['slug'=>'markdown'], ['class'=>'text-muted small', 'target'=>'app-page']); ?>)
				</label>
				<?= tag($body, ['id'=>'comment-body-editor', 'name'=>'body', 'class'=>'editor', 'style'=>'height:100px'], 'textarea'); ?>
				<input type='submit' value='Submit' class='btn btn-primary' />
			</form>
		</div>
		<?php foreach($comments as $comment): ?>
			<?php render_partial('comments/_comment.php', ['comment'=>$comment, 'post'=>$post]); ?>
		<?php endforeach; ?>
	</div>
</div>
