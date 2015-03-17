<?php if (isset($orders)) : ?>
<table class="table">
	<thead>
		<tr>
			<th class="big">Pedidos</th>
			<th><a class="link-ajax" href="<?php echo base_url(); ?>main/displayAjax/<?php echo $_status; ?>/post_status/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>">Estado<i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a></th>
			<th>Lugar</th>
			<th><a class="link-ajax" href="<?php echo base_url(); ?>main/displayAjax/<?php echo $_status; ?>/created_at/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>">Fecha de Pedido<i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a></th>
			<th><a class="link-ajax" href="<?php echo base_url(); ?>main/displayAjax/<?php echo $_status; ?>/published_at/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>">Fecha de Entrega<i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($orders as $item) : ?>

		<tr>
			<td><a href="<?php echo base_url(); ?>main/view/<?php echo $item->id; ?>"><?php echo $item->post_title; ?></a></td>
		<?php
			$status = ($item->post_status === 'initiated') ? 'Iniciado' : 'Finalizado';
		?>
			<td><?php echo $status; ?></td>
			<td><?php echo $item->post_excerpt; ?></td>
			<td><?php echo date('Y-m-d', strtotime($item->created_at)); ?></td>
			<td><?php echo date('Y-m-d', strtotime($item->published_at)); ?></td>
		</tr>

	<?php endforeach; ?>
	</tbody>
</table>

<nav class="main__grid__footer__pagination text-right">
<?php if (isset($_pagination) && strlen($_pagination)) : ?>
	<?php echo $_pagination; ?>
<?php endif; ?>
</nav><!-- end main__grid__footer__pagination -->
<?php else : ?>
	<div class="alert alert-danger">
		<p>No se encontró datos</p>
	</div>
<?php endif; ?>