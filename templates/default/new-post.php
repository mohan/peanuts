<div id='new-post'>
	<h2>New Post</h2>
	<form method='post' action='<?php echo urlto('create-post', NULL, 'post'); ?>'>
		<label class='d-block' for='title' autocomplete='false'>Title</label>
		<input type='text' name='title' class='input' id='title' value='<?php echo htmlentities($_POST['title']);?>' />
		<label class='d-block' for='post-body'>
			Body
			<span class='small'>
				(<a target='_blank' href='<?php echo CONFIG_ROOT_URL; ?>docs.php?file=help/markdown.md'>Markdown</a>)
			</span>
		</label>
		<textarea name='body' id='post-body'><?php echo htmlentities($_POST['body']);?></textarea>
		<input type='submit' value='Post' class='btn btn-primary' />
	</form>
</div>
