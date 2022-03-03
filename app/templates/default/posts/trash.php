<?= html_flash(); ?>

<h1>Trash</h1>

<p class='text-right'><?= linkto('trash/comments', 'Comments') ?></p>

<h2>Posts</h2>
<?php if(sizeof($data) > 0): ?>
	
	<?= tag_table(['title'=>'Title', 'date'=>'Date', 'action'=>'Action'], $data, ['class'=>'table w-100 thead-text-left'], function($record, $header_key){
		switch ($header_key) {
			case 'action':
				return
					formto('post', ['__method'=>'delete', 'post_id'=>$record['id']], ['data-alert'=>'Delete?', 'class'=>'d-inline']) .
					"<input type='submit' value='Delete' class='btn btn-danger' />" .
					"</form>" .
					(
						$_REQUEST['username'] != $record['username'] ? '' :
						formto('post', ['__method'=>'patch', 'post_id'=>$record['id']], ['data-alert'=>'Restore?', 'class'=>'d-inline']) .
						"<input type='submit' value='Restore' class='btn' />" .
						"</form>"
					);

			case 'date':
				return date('M j, Y, g:i a', $record['created_at']) . " (" . date('Y/j/n', $record['updated_at']) . ")";

			case 'title':
				return
					tag($record['title'], [], 'p') .
					tag('#' . $record['id'] . ' / ' . CONFIG_USERS[$record['username']], ['class'=>'text-muted small'], 'p');

			default:
				return htmlentities($record[$header_key]);
		}
	}) ?>

<?php else: ?>

	<?= tag('No posts in trash.') ?>

<?php endif; ?>
