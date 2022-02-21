<?php
// Remove these two lines to remove sample posts
$sample_posts_table = [
	"data_dir" => './',
	"tablename" => 'sample_posts.csv',
	"max_record_width" => 96,
	"columns" => [
			"username"=>"string",
			"title"=>"string",
			"body"=>"json"
	],
	"auto_timestamps" => true
];

$posts = array_merge($posts, csvdb_list($sample_posts_table));
?>

<div id='posts'>

	<div class='border-bottom m-b'>
		<div class='user-container'>
			<h4 class='user-icon' style='margin-top:30px;'>
				<?php echo user_initial($_REQUEST['PEANUTS']['username']);?>
			</h4>
		</div>
		<form method='post' action='<?php echo urlto('create-post', ['redirect'=>urlto('posts')], 'post'); ?>' class='header-container'>
			<label for='quickpost' class='d-block text-muted small'>QuickPost</label>
			<textarea id='quickpost' name='title' class='input' style='height:100px;'><?php echo htmlentities($_POST['title']); ?></textarea>
			<div id='quickpost-strlen' class='small text-muted'></div>
			<input type='submit' value='Post' class='btn btn-primary' />
		</form>
	</div>

	<?php
		foreach($posts as $r_id => $post){
			include $template_path . 'post.php';
		}
	?>
</div>
