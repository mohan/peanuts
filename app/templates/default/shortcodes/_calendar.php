<div class='shortcode-calendar'>
	<h4><?= date('F Y', $month_time) ?></h4>
	<?= tag_table([ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ], $month_table, ['class'=>'table text-center']); ?>
	<ul>
		<?php foreach ($marked_days as $key => $m): ?>
			<?php if($label[$key]): ?>
				<li><?= $m ?>: <?= $label[$key] ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
