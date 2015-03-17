<div class="panel">
	<h2 class="panel__title text-uppercase h4">Mis pedidos</h2>

	<div class="panel__select-month">
		<select name="month" id="js-change-month">
		<?php foreach ($months as $key => $value) : ?>
			<option value="<?php echo $key + 1; ?>"><?php echo $value; ?></option>
		<?php endforeach; ?>
		</select>
	</div>

	<div class="panel__grid">
		<article class="panel__grid__content"></article><!-- end main__grid__content -->
	</div><!-- end panel__grid -->
</div>