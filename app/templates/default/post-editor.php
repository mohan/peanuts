<div id='new-post'>
	<?= tag($_REQUEST['flash'], ['id'=>'flash', 'class'=>'text-center']); ?>
	<h2>
		<?php if($post): ?>
			Edit Post <?= linkto('post', '#'.$post['id'], ['post_id'=>$post['id']]); ?>
		<?php else: ?>
			New Post
		<?php endif; ?>
	</h2>
	<?= $post ? formto('update-post', ['post_id'=>$post['id']]) : formto('create-post'); ?>
		<label class='d-block' for='post-title-editor' autocomplete='false'>Title</label>
		<?= tag($post['title'], ['name'=> 'title', 'class'=>'editor', 'id'=>'post-title-editor'], 'textarea'); ?>
		<label class='d-block' for='post-body-editor'>
			Body
			(<?= linkto('page', 'Markdown', ['slug'=>'markdown'], ['target'=>'app-page']); ?>)
		</label>
		<?= tag($post['body'], ['name'=> 'body', 'id'=>'post-body-editor', 'class'=>'editor'], 'textarea'); ?>
		<input type='submit' value='Post' class='btn btn-primary' />
	</form>
</div>
