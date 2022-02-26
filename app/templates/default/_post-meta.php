<p class='small text-muted'>
	<?= date(date('Y') == date('Y', $post['updated_at'])? 'M j, g:i a' : 'M j, Y g:i a', $post['updated_at']) ;?> /
	<?= linkto('post', '#' . $post['id'], ['post_id'=>$post['id']], ['class'=>'text-muted']); ?> /
	<?= CONFIG_USERS[$post['username']]; ?>
	<?php
		if($post['meta']['c']) {
			echo ' / ';
			echo linkto('post', $post['meta']['c'] . ' Comments', ['post_id'=>$post['id']], ['class'=>'text-muted']);
		}
	?>
	<?php
		if( $show_edit_link && $post['username'] == $_REQUEST['username'] ) {
			echo ' / ';
			echo linkto('edit-post', 'Edit', ['post_id'=>$post['id']], ['class'=>'text-muted']); 
		}
	?>
</p>