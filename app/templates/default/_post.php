<div class='post-panel clear'>
	<div class='user-container'>
		<?= tag(user_initial($post['username']), ['class'=>'user-icon'], 'h4'); ?>
	</div>
	<div class='header-container'>
		<h3>
			<a href='<?= urltoget('post', [id=>$post['id']]); ?>' class='d-block'>
				<?= tag($post['title'], ['class'=>'markdown'], 'pre') ?>
			</a>
		</h3>
		<?php render_partial('_post-meta.php', ['post'=>$post, 'show_edit_link' => $show_edit_link]); ?>
	</div>
</div>
