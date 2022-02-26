<div id='hashtags'>
	<?php if($hashtag): ?>
		<?= tag("#$hashtag", [], 'h2'); ?>
		<div id='posts' class='border-bottom p-b'>
			<?php
				foreach($posts as $post){
					render_partial('_post.php', ['post'=>$post]);
				}
			?>

			<?php if(sizeof($posts) == 0): ?>
				<p class='text-center'>No posts found!</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<h2>Hashtags</h2>
	<p class='text-muted'>Hashtags are tags added to your title. "This is an example post. #example."</p>

	<?php foreach($hashtags as $hashtag): ?>
		<?= linkto('hashtags', "#$hashtag", ['hashtag'=>$hashtag], ['class' => 'btn btn-light']) ?>
	<?php endforeach; ?>

</div>