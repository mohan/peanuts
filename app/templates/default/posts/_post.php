<div class='post-panel clear'>
	<div class='user-container'>
		<?= html_user_icon($post['username']); ?>
	</div>
	<div class='header-container'>
		<h3>
			<a href='<?= url_getpost($post); ?>' class='d-block'>
				<?= html_markdown($post['title']); ?>
			</a>
		</h3>
		<?php render_partial('posts/_post-meta.php', ['post'=>$post, 'show_edit_link' => $show_edit_link]); ?>
	</div>
</div>
