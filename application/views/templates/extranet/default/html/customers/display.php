<form action="" class="frm-search">
	<div class="input-group frm-search__group">
		<input type="text" class="form-control form__input form__group__input" id="js-customer-search-text" data-href="<?php echo base_url(); ?>extranet/customers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_limit ?>/" placeholder="<?php echo $this->lang->line('cms_general_label_search'); ?>..." />
		<span class="input-group-btn form__group__btn">
		<button class="btn btn-default" id="js-customer-search" type="button" data-href="<?php echo base_url(); ?>extranet/customers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_limit ?>/"><i class="fa fa-search"></i></button>
			</span><!-- end input-group-btn -->
	</div><!-- end input-group -->
</form>
<div class="clearfix"></div>

<?php if (!empty($_search) && $_search !== 'all') : ?>
	<h4>Resultado de la búsqueda: <?php echo $_search; ?></h4>
<?php endif; ?>

<?php $totalCustomer = count($_customers); ?>
<?php if (isset($_customers) && $totalCustomer) : ?>
	<?php
		$firstId = $_customers[0]->id;
		$lastId = $_customers[$totalCustomer - 1]->id;
	?>
<table class="table table-hover table-striped">
	<thead>
		<tr>
			<th>
				<input type="checkbox" name="" id="" value="1" /><span class="lbl"></span>
			</th>
			<th><a class="link-ajax" href="<?php echo base_url(); ?>extranet/customers/displayAjax/<?php echo $_status; ?>/id/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>">ID<i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a></th>
			<th><a class="link-ajax" href="<?php echo base_url(); ?>extranet/customers/displayAjax/<?php echo $_status; ?>/name/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_title_customers'); ?><i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a></th>
			<th><?php echo $this->lang->line('cms_general_label_email'); ?></th>
			<th><?php echo $this->lang->line('cms_general_title_customers_contact'); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($_customers as $customer) : ?>
		<tr>
			<td class="text-center"><input type="checkbox" name="" id="" value="<?php echo $customer->id; ?>" /><span class="lbl"></span></td>
			<td class="text-center"><?php echo $customer->id; ?></td>
			<td><?php echo $customer->name; ?></td>
			<td><?php echo $customer->email; ?></td>
			<?php
				$meta = unserialize($customer->meta_value);
			?>
			<td><?php echo $meta['contact']; ?></td>
			<td class="text-center">
				<div class="table__actions">
				<?php if ($this->user->has_permission('edit_customers')) : ?>
					<a href="<?php echo base_url(); ?>extranet/customers/edit/<?php echo $customer->id; ?>" title="Editar al cliente <?php echo $customer->name; ?>"><i class="icon-edit"></i></a>
				<?php endif; ?>
				<?php if ($this->user->has_permission('del_customers')) : ?>
					<a href="javascript:void(0);" class="js-del-customer" data-id="<?php echo $customer->id; ?>"><i class="icon-trash text-danger"></i></a>
				<?php endif; ?>
				</div>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table><!-- end table -->

<aside class="main__grid__footer">
	<div class="row">
		<div class="col-xs-4">
			<div class="main__grid__footer__rows">
				<div class="row">
					<div class="col-xs-4">
						<select class="form-control form__select input-sm" id="js-customer-rows" data-href="<?php echo base_url(); ?>extranet/customers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_limit ?>/<?php echo $_search; ?>">
							<?php
								for ($i = 5; $i <= 30; $i += 5) {
									$selected = ($_limit == $i) ? 'selected="selected"' : '';
									$selected = ($_limit == 0 && $i == 30) ? 'selected="selected"' : $selected;
							?>
								<option value="<?php echo ($i == 30) ? 'all' : $i; ?>" <?php echo $selected; ?>><?php echo ($i == 30) ? 'Todos' : $i; ?></option>
							<?php
								}
							?>
						</select><!-- end form__select -->
					</div><!-- end col-xs-4 -->
					<div class="col-xs-8">
						<p>Mostrando <?php echo $firstId; ?> - <?php echo $lastId; ?> de <?php echo $_total; ?></p>
					</div><!-- end col-xs-8 -->
				</div><!-- end row -->
			</div><!-- end main__grid__footer_rows -->
		</div><!-- end col-xs-4 -->
		<div class="col-xs-8">
			<nav class="main__grid__footer__pagination text-right">
			<?php if (isset($_pagination) && strlen($_pagination)) : ?>
				<?php echo $_pagination; ?>
			<?php endif; ?>
			</nav><!-- end main__grid__footer__pagination -->
		</div><!-- end col-xs-8 -->
	</div>
</aside><!-- end main__grid__footer -->

<?php else : ?>

	<div class="alert alert-danger">
	    <p><?php echo $this->lang->line('cms_general_label_no_found'); ?></p>
	</div>

<?php endif; ?>