<?php
	if(
		strpos($_GET['file'], '..') !== false ||
		!in_array($_GET['file'], [
			'readme.md',

			'help/markdown.md',

			'lib/helpers.php',
			'lib/csvdb.php',
			'app/functions.php',
			'app/data.php',
			'index.php',
		])
	) exit;

	require_once('./lib/helpers.php');
	require_once('./app/functions.php');

	filter_set_config();
	filter_set_username();
?>
<html>
<head>
	<title>Peanuts Docs<?php echo ': ' . $_GET['file']; ?></title>
	<style>
		body{ background:#f9f9f9; line-height:130%; font-size: 1.05em; margin:30px 0 100px 0; }
		h1,h2{ padding-bottom: 20px;  border-bottom:1px solid #ccc; }
		pre{ tab-size:4; font-size: 1.1em; line-height:175%; }
		.sourcecode .function_label, .sourcecode .curlybrace, .sourcecode .parenthesis, .sourcecode .bracket{ color: #007700; }
		.sourcecode .function_name, .sourcecode .function_args{ color: #0000BB; }
	</style>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>
	<div style='margin: 10px auto; max-width: 80%; border: 1px solid #ddd; border-radius:4px; padding: 30px 50px;'>
		<h1>Peanuts Docs</h1>
		<p>License: GPL</p>
		<ul style='border:1px solid #ccc; width:200px; padding-top: 20px; padding-bottom: 20px; margin-top: 20px; margin-bottom: 50px; line-height: 1.7em;'>
			<li><a href='?file=readme.md'>Readme</a></li>
			<li>
				Help
				<ul>
					<li><a href='?file=help/markdown.md'>markdown.md</a></li>
				</ul>
			</li>
			<li>
				Source
				<ul>
					<li><a href='?file=lib/helpers.php'>lib/helpers.php</a></li>
					<li><a href='?file=lib/csvdb.php'>lib/csvdb.php</a></li>
					<li><a href='?file=app/functions.php'>app/functions.php</a></li>
					<li><a href='?file=app/data.php'>app/data.php</a></li>
					<li><a href='?file=index.php'>index.php</a></li>
				</ul>
			</li>
		</ul>
		<h2><?php echo $_GET['file']; ?></h2>
		<pre class='sourcecode'><?php echo sourcecode(htmlentities(file_get_contents('./' . $_GET['file']))); ?></pre>
	</div>
</body>


<?php

function sourcecode($text)
{
	return preg_replace([
							"/\n(function)\s(.+)\((.*)\)/"
						],
						[
						 	"\n<span class='function_label'>$1</span> <span class='function_name'>$2</span><span class='parenthesis'>(</span><span class='function_args'>$3</span><span class='parenthesis'>)</span>"
						],
						$text);
}