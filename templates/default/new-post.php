<div id='new-post'>
	<h2>New Post</h2>
	<form method='post' action='<?php echo urlto('create-post', NULL, 'post'); ?>'>
		<label class='d-block' for='title'>Title</label>
		<input type='text' name='title' class='input' id='title' />
		<label class='d-block' for='body'>
			Body
			<a class='small' href='<?php echo urlto_template_asset('../../help/markdown.txt'); ?>'>
				(Markdown supported)
			</a>
		</label>
		<textarea name='body' id='body'></textarea>
		<input type='submit' value='Post' class='btn btn-primary' />
	</form>
</div>
