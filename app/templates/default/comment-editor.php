<div id='new-post'>
	<h2>Edit Comment</h2>
	<?= formto('update-comment', ['id' => $id, 'post_id'=>$_GET['post_id']], ['class'=>'header-container']); ?>
		<label for='comment-body-editor' class='d-block text-muted small'>Edit Comment</label>
		<?php tag($body, ['id'=>'comment-body-editor', 'name'=>'body', 'class'=>'editor', 'style'=>'height:100px'], 'textarea'); ?>
		<input type='submit' value='Submit' class='btn btn-primary' />
	</form>
</div>
