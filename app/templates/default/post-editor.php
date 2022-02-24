<div id='new-post'>
	<h2>
		<?php if($id): ?>
			Edit Post <?php linkto('post', "#$id", ['id'=>$id]); ?>
		<?php else: ?>
			New Post
		<?php endif; ?>
	</h2>
	<? $id ? formto('update-post', ['id'=>$id]) : formto('create-post'); ?>
		<label class='d-block' for='post-title-editor' autocomplete='false'>Title</label>
		<?php tag($title, ['name'=> 'title', 'class'=>'editor', 'id'=>'post-title-editor'], 'textarea'); ?>
		<label class='d-block' for='post-body-editor'>
			Body
			(<a target='app-docs' href='<?= CONFIG_ROOT_URL; ?>docs.php?file=app/help/markdown.md#content'>Markdown</a>)
		</label>
		<?php tag($body, ['name'=> 'body', 'id'=>'post-body-editor', 'class'=>'editor'], 'textarea'); ?>
		<input type='submit' value='Post' class='btn btn-primary' />
	</form>
</div>
