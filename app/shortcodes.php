<?php

function shortcodes_list()
{
	return ['post', 'calendar'];
}


function shortcode_post($args)
{
	$id = intval(str_replace('#', '', $args[0]));
	
	$post = data_post_read($id, ['id', 'username', 'title', 'meta', 'updated_at']);

	return "<div class='shortcode-post'>" . render_partial('posts/_post.php', ['post'=>$post], true) . "</div>";
}


function shortcode_calendar($args)
{
	$args['today'] = time();
	if(!$args['year']) $args['year'] = date('Y', $args['today']);
	if(!$args['month']) $args['month'] = date('n', $args['today']);

	$args['year'] = intval($args['year']);
	$args['month'] = intval($args['month']);

	$args['month_time'] = mktime(0, 0, 0, $args['month'], 1, $args['year']);

	if(!$args['mark']) $args['mark'] = [];
	if(!is_array($args['mark'])) $args['mark'] = [ $args['mark'] ];
	if(!$args['label']) $args['label'] = [];
	if(!is_array($args['label'])) $args['label'] = [ $args['label'] ];

	extract($args);
	$month_table = [];
	
	$i = 0;
	$week_day_number = date('N', $month_time);
	for ($j=1; $week_day_number != 7 && $j <= $week_day_number; $j++) {
		$month_table[$i][] = '';
	}

	for($day_number=1; $day_number <= 31; $day_number++){
		$day_time = mktime(0, 0, 0, $month, $day_number, $year);
		if($month != date('n', $day_time)) break;

		$week_day_number = date('N', $day_time);

		if($week_day_number == 7) $i++;

		$mark_class = in_array($day_number, $mark) ? 'marked' : '';
		$month_table[$i][] = "<span class='$mark_class'>$day_number</span>";
	}

	for ($j=$week_day_number+1; $j < 7; $j++) {
		$month_table[$i][] = '';
	}

	$marked_days = [];
	foreach ($mark as $key => $m) {
		$marked_days[] = date('D jS', mktime(0, 0, 0, $month, $m, $year));
	}

	return render_partial('shortcodes/_calendar.php', [
		'month_time' => $month_time, 'month_table' => $month_table, 'marked_days' => $marked_days, 'label' => $label
	], true);
}
