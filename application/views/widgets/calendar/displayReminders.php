<?php if (isset($reminders)) : ?>

	<?php foreach ($reminders as $rem) : ?>

		<article class="list-reminder">

			<h4><?php echo date('d', strtotime($rem->published_at)); ?> / <?php echo date('m', strtotime($rem->published_at)); ?></h4>

			<h3><?php echo $rem->post_title; ?></h3>

			<p><a href="#">Ver regalos</a></p>

		</article><!-- end list-reminder -->

	<?php endforeach; ?>

<?php endif; ?>