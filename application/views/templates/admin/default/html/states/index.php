<div class="row">

	<div class="col-sm-12">

		<div class="panel">

			<div class="panel-heading">
				<h2>
					<?php echo $this->lang->line('cms_general_label_status'); ?>
					<?php if ($this->user->has_permission('add_states')) : ?>
						<a href="<?php echo base_url(); ?>admin/states/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?> <i class="fa fa-plus"></i></a>
					<?php endif; ?></h2>
			</div><!-- end panel-heading -->

			<div class="panel-body">
				<div class="content"></div><!-- end content -->
			</div><!-- end panel-body -->
		</div><!-- end panel -->

	</div><!-- end col-sm-12 -->
</div><!-- end row -->