<div id='posts'>

	<div class='border-bottom m-b'>
		<div class='user-container'>
			<?php tag(user_initial($_REQUEST['username']), ['class'=>'user-icon', 'style'=>'margin-top:30px;'], 'h4'); ?>
		</div>
		<?= formto('create-quick-post', NULL, ['class'=>'header-container']); ?>
			<label for='quickpost-editor' class='d-block text-muted small'>
				QuickPost (<?php linkto('hashtags', 'Hashtags', [], ['class'=>'text-muted']); ?>)
			</label>
			<?php tag($_POST['title'], ['name'=> 'title', 'id'=>'quickpost-editor', 'class'=>'input', 'style'=>'height:100px;'], 'textarea'); ?>
			<div id='quickpost-strlen' class='small text-muted'></div>
			<input type='submit' value='Post' class='btn btn-primary' />
		</form>
	</div>

	<?php
		foreach($posts as $post){
			render_partial('_post.php', ['post'=>$post]);
		}
	?>

	<?php if(sizeof($posts) == 0): ?>
		<p class='text-center'>No posts found!</p>
	<?php endif; ?>

	<div class='pager'>
		<?php if($page > 1) echo linkto('posts', 'Previous Page', ['page'=>$page-1], ['class'=>'btn']); ?>
		<?php if($page < data_post_pages_max($per_page)) echo linkto('posts', 'Next Page', ['page'=>$page+1], ['class'=>'btn']); ?>
	</div>
</div>
