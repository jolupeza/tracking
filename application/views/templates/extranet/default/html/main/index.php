<div class="row">
	<div class="col-xs-12">
		<header class="main__header">
			<h2 class="main__header__title h3"><?php echo $this->lang->line('cms_general_title_orders'); ?></h2>
			<?php if ($this->user->has_permission('add_orders')) : ?>
			<div class="main__header__buttons">
				<a href="<?php echo base_url(); ?>extranet/orders" class="button button--blue"><?php echo $this->lang->line('cms_general_label_add_new'); ?></a>
				<!--a href="" class="button button--red"><?php //echo $this->lang->line('cms_general_label_view_all'); ?></a-->
			</div>
			<?php endif; ?>
			<hr class="hr" />
		</header>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<section class="main__grid">
			<div class="col-xs-12">
				<article class="main__grid__content"></article><!-- end main__grid__content -->
			</div><!-- end col-xs-12 -->
		</section>
	</div>
</div>