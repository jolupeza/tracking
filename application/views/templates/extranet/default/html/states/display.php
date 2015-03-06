<form action="" class="frm-search">
	<div class="input-group frm-search__group">
		<input type="text" class="form-control form__input form__group__input" id="js-state-search-text" data-href="<?php echo base_url(); ?>extranet/states/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_limit ?>/" placeholder="<?php echo $this->lang->line('cms_general_label_search'); ?>..." />
		<span class="input-group-btn form__group__btn">
		<button class="btn btn-default" id="js-state-search" type="button" data-href="<?php echo base_url(); ?>extranet/states/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_limit ?>/"><i class="fa fa-search"></i></button>
			</span><!-- end input-group-btn -->
	</div><!-- end input-group -->
</form>
<div class="clearfix"></div>

<?php if (!empty($_search) && $_search !== 'all') : ?>
	<h4>Resultado de la búsqueda: <?php echo $_search; ?></h4>
<?php endif; ?>

<?php $totalStates = count($_states); ?>
<?php if (isset($_states) && $totalStates) : ?>
	<?php
		$firstId = $_states[0]->id;
		$lastId = $_states[$totalStates - 1]->id;
	?>
<table class="table table-hover table-striped">
	<thead>
		<tr>
			<th>
				<input type="checkbox" name="" id="" value="1" /><span class="lbl"></span>
			</th>
			<th><a class="link-ajax" href="<?php echo base_url(); ?>extranet/states/displayAjax/<?php echo $_status; ?>/id/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>">ID<i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a></th>
			<th><a class="link-ajax" href="<?php echo base_url(); ?>extranet/states/displayAjax/<?php echo $_status; ?>/post_title/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_status_name'); ?><i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a></th>
			<th><a class="link-ajax" href="<?php echo base_url(); ?>extranet/states/displayAjax/<?php echo $_status; ?>/menu_order/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>">Orden<i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($_states as $row) : ?>
		<tr>
			<td class="text-center"><input type="checkbox" name="" id="" value="<?php echo $row->id; ?>" /><span class="lbl"></span></td>
			<td class="text-center"><?php echo $row->id; ?></td>
			<td class="js-change-name-state" data-name="<?php echo $row->post_title; ?>">
				<p><?php echo $row->post_title; ?></p>
				<div class="table__actions hide">
					<input type="text" class="form__input form-control" id="js-edit-name-state-<?php echo $row->id; ?>" value="<?php echo $row->post_title; ?>" />
					<button class="button button--default js-change-name-state-update" data-id="<?php echo $row->id; ?>"><?php echo $this->lang->line('cms_general_label_update'); ?></button>
					<button class="button button--red js-change-name-state-cancel"><?php echo $this->lang->line('cms_general_label_cancel'); ?></button>
				</div>
			</td>
			<td class="text-center">
				<?php if ($row->menu_order > 1) : ?>
					<a href="#" class="text-success js-change-order-down" data-id="<?php echo $row->id; ?>" data-order="<?php echo $row->menu_order; ?>"><i class="fa fa-arrow-down"></i></a>
				<?php endif; ?>
				<?php echo $row->menu_order; ?>
				<?php if ($row->menu_order != $_total) : ?>
					<a href="#" class="text-danger js-change-order-up" data-id="<?php echo $row->id; ?>" data-order="<?php echo $row->menu_order; ?>"><i class="fa fa-arrow-up"></i></a>
				<?php endif; ?>
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
						<select class="form-control form__select input-sm" id="js-state-rows" data-href="<?php echo base_url(); ?>extranet/states/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_limit ?>/<?php echo $_search; ?>">
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