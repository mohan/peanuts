<p class='small text-muted'>
	<?= date(date('Y') == date('Y', $post['updated_at'])? 'M j, g:i a' : 'M j, Y g:i a', $post['updated_at']) ;?> /
	<?= link_getpost('#'.$post['id'], $post, ['class'=>'text-muted']); ?> /
	<?= CONFIG_USERS[$post['username']]; ?>
	<?php
		if($post['meta']['c']) {
			echo ' / ';
			echo link_getpost($post['meta']['c'] . ' Comments', $post, ['class'=>'text-muted']);
		}
	?>
	<?php
		if( $show_edit_link && $post['username'] == $_REQUEST['username'] ) {
			echo ' / ';
			echo link_editpost('Edit', $post, ['class'=>'text-muted']);
		}
	?>
</p>