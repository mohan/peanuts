<?= html_flash(); ?>

<h1>Trash</h1>

<p class='text-right'><?= linkto('trash/posts', 'Posts') ?></p>

<h2>Comments</h2>
<?php if(sizeof($data) > 0): ?>
	
	<?= tag_table(['body'=>'Comment', 'date'=>'Date', 'action'=>'Action'], $data, ['class'=>'table w-100 thead-text-left'], function($record, $header_key){
		switch ($header_key) {
			case 'action':
				return
					formto('comment', ['__method'=>'delete', 'post_id'=>$record['post']['id'], 'comment_id'=>$record['id']], ['data-alert'=>'Delete?', 'class'=>'d-inline']) .
						"<input type='submit' value='Delete' class='btn btn-danger' />" .
					"</form>" .
					(
						$_REQUEST['username'] != $record['username'] ? '' :
						formto('comment', ['__method'=>'patch', 'post_id'=>$record['post']['id'], 'comment_id'=>$record['id']], ['data-alert'=>'Restore?', 'class'=>'d-inline']) .
							"<input type='submit' value='Restore' class='btn' />" .
						"</form>"
					);

			case 'date':
				return date('M j, Y, g:i a', $record['created_at']) . " (" . date('Y/j/n', $record['updated_at']) . ")";

			case 'body':
				return 
					tag($record['body'], [], 'p') .
					tag('#' . $record['post']['id'] . ' / ' . CONFIG_USERS[$record['username']] . ', ' . $record['post']['title'], ['class'=>'text-muted small'], 'p');

			default:
				return htmlentities($record[$header_key]);
		}
	}) ?>

<?php else: ?>

	<?= tag('No posts in trash.') ?>

<?php endif; ?>
