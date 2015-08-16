	<div class="search-status">
		<div class="row">
			<div class="col-sm-8">
				<ul class="list-inline mnu-status">
					<li <?php echo ($_status === 'all') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/states/displayAjax"><?php echo $this->lang->line('cms_general_label_all'); ?> (<?php echo $_num_total; ?>)</a></small></li>

					<?php if ($_active > 0) : ?>
					<li <?php echo ($_status === 'publish') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/states/displayAjax/publish"><?php echo $this->lang->line('cms_general_label_active'); ?> (<?php echo $_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_no_active > 0) : ?>
					<li <?php echo ($_status === 'draft') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/states/displayAjax/draft"><?php echo $this->lang->line('cms_general_label_no_active'); ?> (<?php echo $_no_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_trash > 0) : ?>
					<li <?php echo ($_status === 'trash') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/states/displayAjax/trash"><?php echo $this->lang->line('cms_general_label_trush'); ?> (<?php echo $_trash; ?>)</a></small></li>
					<?php endif; ?>
				</ul>
			</div><!-- end col-sm-8 -->

			<div class="col-sm-4">
				<div class="input-group">
	  				<input type="text" class="form-control text-search" id="js-state-search-text" />
	  				<span class="input-group-addon"><button type="button" id="js-state-search" data-href="<?php echo base_url(); ?>admin/states/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_limit ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</div>
		</div>
	</div><!-- end search-status -->

	<div class="filters-goto">
		<div class="row">
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-6">

					</div><!-- end col-sm-6 -->
					<div class="col-sm-6"></div>
				</div>
			</div><!-- end col-sm-8 -->
			<div class="col-sm-4">
			<?php if (isset($_pagination) && strlen($_pagination)) : ?>
				<div class="container-pagination text-right">
					<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
					<small>
						<input type="text" class="goto" value="<?php echo $this->pagination->cur_page; ?>" />
						<?php echo ' ' . $this->lang->line('cms_general_label_of') . ' ' . ceil($this->pagination->total_rows / $this->pagination->per_page); ?>
						<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/states/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
					</small>
				</div>
			<?php endif; ?>
			</div><!-- end col-sm-4 -->
		</div><!-- end row -->
	</div><!-- end filters-goto -->

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th class="th-small">
					<a class="link-ajax" href="<?php echo base_url(); ?>admin/states/displayAjax/<?php echo $_status; ?>/id/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>">Id <i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a>
				</th>
				<th>
					<a class="link-ajax" href="<?php echo base_url(); ?>admin/states/displayAjax/<?php echo $_status; ?>/post_title/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_status_name'); ?><i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a>
				</th>
				<th>
					<a class="link-ajax" href="<?php echo base_url(); ?>admin/states/displayAjax/<?php echo $_status; ?>/menu_order/<?php echo ($_sort_order === 'asc') ? 'desc' : 'asc'; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>">Orden<i class="fa <?php echo ($_sort_order == 'asc') ? 'fa-angle-down' : 'fa-angle-up' ?>"></i></a>
				</th>
				<th><?php echo $this->lang->line('cms_general_label_active'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php if (isset($_states) && sizeof($_states) > 0) : ?>
			<?php foreach ($_states as $item) : ?>
			<tr>
				<td class="text-center"><?php echo $item->id; ?></td>
				<td class="view-option-post">
				<?php if ($this->user->has_permission('edit_states')) : ?>
					<a href="<?php echo base_url(); ?>admin/states/edit/<?php echo $item->id; ?>"><?php echo $item->post_title; ?></a>
				<?php else : ?>
					<?php echo $item->post_title; ?>
				<?php endif; ?>

				<?php if ($this->user->has_permission('edit_states') || $this->user->has_permission('del_states')) : ?>
					<div class="opt-post">
						<ul class="list-inline">

					<?php if ($item->post_status == 'trash') : ?>

						<?php if ($this->user->has_permission('edit_states')) : ?>
							<li>
								<small><a class="js-change-status" href="#" data-status="draft" data-id="<?php echo $item->id; ?>" data-return="admin/states/displayAjax"><?php echo $this->lang->line('cms_general_label_restore'); ?></a></small>
							</li>
						<?php endif; ?>
						<?php if ($this->user->has_permission('del_states')) : ?>
							<li>
								<small>
									<a class="text-danger js-state-del" href="#" data-id="<?php echo $item->id; ?>"><?php echo $this->lang->line('cms_general_label_delete_permanent'); ?></a>
								</small>
							</li>
						<?php endif; ?>

					<?php else : ?>

						<?php if ($this->user->has_permission('edit_states')) : ?>
							<li><small><a href="<?php echo base_url(); ?>admin/states/edit/<?php echo $item->id; ?>"><?php echo $this->lang->line('cms_general_label_edit'); ?></a></small></li>
						<?php endif; ?>
						<?php if ($this->user->has_permission('del_states')) : ?>
							<li>
								<small>
									<a class="text-danger js-change-status" href="#" data-status="trash" data-id="<?php echo $item->id; ?>" data-return="admin/states/displayAjax/trash"><?php echo $this->lang->line('cms_general_label_trush'); ?></a>
								</small>
							</li>
						<?php endif; ?>

					<?php endif; ?>
						</ul>
					</div><!-- end opt-post -->
				<?php endif; ?>
				</td>

				<td class="text-center">
					<?php if ($item->menu_order > 1) : ?>
						<a href="#" class="text-success js-change-order-down" data-id="<?php echo $item->id; ?>" data-order="<?php echo $item->menu_order; ?>"><i class="fa fa-arrow-down"></i></a>
					<?php endif; ?>
					<?php echo $item->menu_order; ?>
					<?php if ($item->menu_order != $_total) : ?>
						<a href="#" class="text-danger js-change-order-up" data-id="<?php echo $item->id; ?>" data-order="<?php echo $item->menu_order; ?>"><i class="fa fa-arrow-up"></i></a>
					<?php endif; ?>
				</td>

				<td class="text-center">
				<?php if ($this->user->has_permission('publish_states')) : ?>
					<?php $changeStatus = ($item->post_status === 'publish') ? 'draft' : 'publish'; ?>
					<a href="#" data-status="<?php echo $changeStatus; ?>" data-id="<?php echo $item->id; ?>" class="js-change-status" data-return="admin/states/displayAjax"><?php echo ($item->post_status == 'publish') ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></a>
				<?php else : ?>
					<?php echo ($item->post_status == 'publish') ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?>
				<?php endif; ?>
				</td>

				<td class="text-center">
				<?php if ($this->user->has_permission('edit_states')) : ?>
					<div class="table__actions">
						<a href="<?php echo base_url(); ?>admin/states/edit/<?php echo $item->id; ?>" title="Editar al cliente <?php echo $item->post_title; ?>"><i class="fa fa-pencil-square-o"></i></a>
					</div>
				<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-danger" colspan="4"><?php echo $this->lang->line('cms_general_label_no_found'); ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<?php if (isset($_pagination) && strlen($_pagination)) : ?>
	<div class="pull-right container-pagination"><span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?></div>
	<?php endif; ?>