<div id='new-post'>
	<?= tag($_REQUEST['flash'], ['id'=>'flash', 'class'=>'text-center']); ?>
	<h2>Edit Comment #<?= $comment['id'] ?></h2>
	<?= formto('update-comment', ['id' => $comment['id'], 'post_id'=>$post_id, ['class'=>'header-container']); ?>
		<label for='comment-body-editor' class='d-block text-muted small'>Edit Comment</label>
		<?= tag($$comment['body'], ['id'=>'comment-body-editor', 'name'=>'body', 'class'=>'editor', 'style'=>'height:100px'], 'textarea'); ?>
		<input type='submit' value='Submit' class='btn btn-primary' />
	</form>
</div>
