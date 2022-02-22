<div id='posts'>

	<div class='border-bottom m-b'>
		<div class='user-container'>
			<h4 class='user-icon' style='margin-top:30px;'>
				<?php echo user_initial($_REQUEST['APP']['username']);?>
			</h4>
		</div>
		<form method='post' action='<?php echo urlto('create-quick-post', null, 'post'); ?>' class='header-container'>
			<label for='quickpost' class='d-block text-muted small'>QuickPost</label>
			<textarea id='quickpost' name='title' class='input' style='height:100px;'><?php echo htmlentities($_POST['title']); ?></textarea>
			<div id='quickpost-strlen' class='small text-muted'></div>
			<input type='submit' value='Post' class='btn btn-primary' />
		</form>
	</div>

	<?php
		foreach($posts as $post){
			include template_filepath('_post.php');
		}
	?>

	<div class='pager'>
		<?php if($page > 1) linkto('Previous Page', 'posts', ['page'=>$page-1], 'btn'); ?>
		<?php if($page < data_post_pages_max($per_page)) linkto('Next Page', 'posts', ['page'=>$page+1], 'btn'); ?>
	</div>
</div>
