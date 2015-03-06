<div class="calendar">
	<?php echo $calendar; ?>

	<?php if (isset($reminders)) : ?>

		<aside class="wrapper-list-reminder">

		<?php foreach ($reminders as $rem) : ?>

			<article class="list-reminder">

				<h4><?php echo date('d', strtotime($rem->published_at)); ?> / <?php echo date('m', strtotime($rem->published_at)); ?></h4>

				<h3><?php echo $rem->post_title; ?></h3>

				<p><a href="#">Ver regalos</a></p>

			</article><!-- end list-reminder -->

		<?php endforeach; ?>

		</aside><!-- end wrapper-list-reminder -->

	<?php endif; ?>

	<div class="sel-month hidden">
		<ul class="list-inline">
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/01'; ?>">Enero</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/02'; ?>">Febrero</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/03'; ?>">Marzo</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/04'; ?>">Abril</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/05'; ?>">Mayo</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/06'; ?>">Junio</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/07'; ?>">Julio</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/08'; ?>">Agosto</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/09'; ?>">Setiembre</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/10'; ?>">Octubre</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/11'; ?>">Noviembre</a></li>
			<li class="list-month"><a href="<?php echo base_url() . 'reminders/getDisplayCalendar/display/calendar/2015/12'; ?>">Diciembre</a></li>
		</ul>
	</div>

</div><!-- end calendar -->