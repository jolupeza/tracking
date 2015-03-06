<div class="row">

	<div class="col-sm-12">

		<div class="panel">

			<div class="panel-heading">
				<h2>
					<?php echo $this->lang->line('cms_general_title_subscribers'); ?>
					<?php if ($this->user->has_permission('create_subs')) : ?>
						<a href="<?php echo base_url(); ?>admin/subscribers/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?> <i class="fa fa-plus"></i></a>

						<a href="<?php echo base_url(); ?>admin/subscribers/addMulti" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_load_multiple'); ?> <i class="fa fa-plus"></i></a>
					<?php endif; ?></h2>
			</div><!-- end panel-heading -->

			<div class="panel-body">
				<div class="content"></div><!-- end content -->
			</div><!-- end panel-body -->
		</div><!-- end panel -->

	</div><!-- end col-sm-12 -->
</div><!-- end row -->