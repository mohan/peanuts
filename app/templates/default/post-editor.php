<div id='new-post'>
	<h2><?php echo $id ? "Edit Post #$id" : 'New Post'; ?></h2>
	<?= $id ? formto('update-post', ['id'=>$id]) : formto('create-post'); ?>
		<label class='d-block' for='title' autocomplete='false'>Title</label>
		<?php tag($title, ['type'=>'text', 'name'=> 'title', 'class'=>'input', 'id'=>'title'], 'input'); ?>
		<label class='d-block' for='post-body-editor'>
			Body
			<span class='small'>
				(<a target='_blank' href='<?= CONFIG_ROOT_URL; ?>docs.php?file=app/help/markdown.md'>Markdown</a>)
			</span>
		</label>
		<?php tag($body, ['name'=> 'body', 'id'=>'post-body-editor', 'class'=>'editor'], 'textarea'); ?>
		<input type='submit' value='Post' class='btn btn-primary' />
	</form>
</div>
