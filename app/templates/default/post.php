<div id='post'>
	<div id='post-<?php echo $r_id; ?>'>
		<h4 class='user-icon'>
			<?php echo user_initial($post['username']);?>
		</h4>
		<h3 class='title'>
			<?php linkto($post['title'], "post", [id=>$post['r_id']], 'd-block');?>
		</h3>
		<p class='small text-muted'>
			<?php echo CONFIG_USERS[$post['username']]; ?> /
			<?php echo date('M j, Y, g:i a', $post['created_at']);?>
		</p>

		<pre class='body'><?php echo $post['body']; ?></pre>
	</div>
</div>
