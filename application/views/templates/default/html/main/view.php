<div class="panel">
	<div class="row">
		<div class="col-xs-4">
			<p class="panel__info"><span>Pedido: </span><?php echo $order->post_title; ?></p>
		</div>
		<div class="col-xs-4">
			<p class="panel__info"><span>Fecha del Pedido: </span><?php echo date('d-m-Y', strtotime($order->created_at)); ?></p>
		</div>
		<div class="col-xs-4">
			<p class="panel__info"><span>Fecha de Entrega: </span><?php echo date('d-m-Y', strtotime($order->published_at)); ?></p>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<p class="panel__info"><span>Lugar de destino: </span><?php echo $order->post_excerpt; ?></p>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<p class="panel__info"><span>Detalle del pedido: </span></p>
			<p class="panel__info"><?php echo $order->post_content; ?></p>
		</div>
	</div>
</div>

<h2 class="text-uppercase h4 main__title">Estado del pedido:</h2>

<div class="main__states">
<?php
	$now        = new DateTime('now');
	$obsLast    = '';
	$titleState = '';
	$z          = 0;
	$total      = count($statesInfo);
?>
<?php foreach ($statesInfo as $key => $value) : ?>
	<?php
		$success = '';
		$active = '';
		$stateSuccess = '';
		$date = new DateTime($value[2] . ' 00:00:00');
		if ($date <= $now) {
			$success = 'main__states__item__state--success';
			$active  = 'js-view-obs-state';
			$obsLast = $value[0];
			$titleState = $value[3];
			$stateSuccess = 'success';
		}

		$dateActive = ($activeDate === $key) ? 'active' : '';
	?>

	<!-- Pensar para solo mostrar triangulo al estado activo -->
	<article class="main__states__item <?php echo $dateActive . ' ' . $stateSuccess; ?>">
		<p class="main__states__item__date text-center">
		<?php if ($z > 0 && $z != $total - 1) echo 'Fecha estimada <br />';?>
			<?php echo date('d-m-Y', strtotime($value[2])); ?>
		</p>
		<hr class="main__states__item__state <?php echo $success; ?>" />
		<figure class="main__states__item__img <?php echo $active; ?> main__states__item__img--<?php echo $key; ?>" data-id="<?php echo $key; ?>" data-title="<?php echo $value[3]; ?>" data-obs="<?php echo $value[0]; ?>" style="background-image: url('<?php echo $value[4]; ?>');">
			<!-- <img src="<?php //echo $value[4]; ?>" class="img-responsive" /> -->
		</figure><!-- end .main__states_item__img -->
		<h5 class="main__states__item__title text-center"><?php echo $value[3]; ?></h5>
	</article><!-- end main__states__item -->
	<?php $z++; ?>
<?php endforeach; ?>
</div><!-- end main__states -->

<h3 class="text-uppercase h5 main__title" id="js-title-obs">Observaciones de <?php echo $titleState; ?>:</h3>

<textarea class="main__obs form__input form-control" id="js-text-obs" row="10" disabled><?php echo $obsLast; ?></textarea>