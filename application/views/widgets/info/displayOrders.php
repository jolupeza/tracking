<div class="widget">

<?php if($orders) : ?>
    <h3 class="h4 widget__title text-uppercase">Mis pedidos</h3>

    <?php foreach ($orders as $ord) : ?>

	<div class="widget__checkbox__group">
		<input type="radio" name="list_orders" id="li-<?php echo $ord->id; ?>" value="<?php echo $ord->id; ?>" /><span class="lbl"><?php echo $ord->post_title; ?></span>
	</div><!-- end .widget__checkbox__group -->

	<?php endforeach; ?>

	<p class="text-center"><a href="<?php echo base_url(); ?>main" class="button button--default">Ver más proyectos</a></p>

<?php else : ?>
	<h3 class="h4 widget__title text-uppercase">No tiene más pedidos</h3>
<?php endif; ?>

</div><!-- end widget -->