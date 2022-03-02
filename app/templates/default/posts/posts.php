<?= html_flash(); ?>

<?php if($banner_post): ?>
	<div class='post-panel' style='margin-bottom: 30px; padding:40px 20px 20px 20px;'>
		<?= tag($banner_post['title'], ['class'=>'post-title', 'style'=>'font-size: 1.1em; padding-bottom:10px;'], 'h3'); ?>
		<?= html_markdown($banner_post['body']); ?>
		<div class='m-t text-right small text-muted'>
			<?= linkto('post', 'View', ['post_id'=>CONFIG_BANNER_POST_ID], ['class'=>'text-muted']); ?> /
			<?= linkto('edit-post', 'Edit Banner', ['post_id'=>CONFIG_BANNER_POST_ID], ['class'=>'text-muted']); ?>
		</div>
	</div>
<?php endif; ?>

<div id='posts'>

	<div class='post-panel border-bottom m-b'>
		<div class='user-container' style="margin-top:10px;">
			<?= html_user_icon($_REQUEST['username']) ?>
		</div>
		<div class='header-container'>
			<?= formto('quick-post'); ?>
				<label for='quickpost-editor' class='d-block text-muted small'>
					QuickPost (<?= linkto('hashtags', 'Hashtags', [], ['class'=>'text-muted']); ?>)
				</label>
				<?= tag($_POST['title'], ['name'=> 'title', 'id'=>'quickpost-editor', 'class'=>'input', 'style'=>'height:100px;'], 'textarea'); ?>
				<div id='quickpost-strlen' class='small text-muted'></div>
				<input type='submit' value='Post' class='btn btn-primary' />
			</form>
		</div>
	</div>

	<?php
		foreach($posts as $post){
			render_partial('posts/_post.php', ['post'=>$post]);
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
