<div id='new-post'>
	<?= html_flash(); ?>
	<h2>Edit Comment #<?= $comment['id'] ?></h2>
	<?= formto('comment', ['comment_id' => $comment['id'], 'post_id'=>$post_id, '__method'=>'patch'], ['class'=>'header-container']); ?>
		<label for='comment-body-editor' class='d-block text-muted small'>Edit Comment</label>
		<?= tag($comment['body'], ['id'=>'comment-body-editor', 'name'=>'body', 'class'=>'editor', 'style'=>'height:100px'], 'textarea'); ?>
		<input type='submit' value='Submit' class='btn btn-primary' />
	</form>
</div>
