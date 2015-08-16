	<div class="row">

		<div class="col-md-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_title_edit_state'); ?> <?php //if ($this->user->has_permission('create_subs')) : ?><a href="<?php echo base_url(); ?>admin/states/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?> <i class="fa fa-plus"></i></a><?php //endif; ?></h2>

					<?php if (validation_errors()) : ?>
		            <div class="alert alert-danger">
		                <?php echo validation_errors('<p><small>', '</small></p>'); ?>
		            </div>
		            <?php endif; ?>

				</div><!-- end panel-heading -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->

	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-body">

					<?php echo form_open('', array('id' => 'js-frm-states', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">

						<div class="col-sm-8">

							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_name'), 'state_name', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'state_name', 'name' => 'state_name', 'class' => 'form-control'), $_state->post_title, 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

							<!-- Status -->
						  	<div class="form-group">
			    				<div class="col-sm-offset-3 col-sm-9">
			      					<div class="checkbox">
			        					<label>
			        						<?php
			        							$checked = ($_state->post_status === 'publish') ? TRUE : FALSE;
			        							$attr = array(
			        								'name'			=>	'state_status',
			        								'id'			=>	'state_status',
			        								'value'			=>	'publish',
			        								'checked'		=>	$checked,
			        								'data-off-text'	=>  "<i class='fa fa-times'></i>",
			        								'data-on-text'	=>	"<i class='fa fa-check'></i>",
			        							);
			        						?>
			          						<?php echo form_checkbox($attr); ?> <?php echo $this->lang->line('cms_general_label_active'); ?>?
			        					</label><!-- end label -->
			      					</div><!-- end checkbox -->
			    				</div><!-- end col-sm-9 -->
			  				</div><!-- end form-group -->

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_add'), 'content' => $this->lang->line('cms_general_label_add'))); ?>

				  		</div><!-- end col-sm-8 -->

				  		<div class="col-sm-4">

				  			<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_label_featured_image'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">
										<!-- State Avatar -->
										<div class="form-group">
											<div class="input-group">
											  	<input type="text" class="form-control" id="fieldID" name="state_avatar" id="state_avatar" value="<?php echo $_state->guid; ?>" placeholder="<?php echo $this->lang->line('cms_general_label_avatar'); ?>">
											  	<a class="btn iframe-btn input-group-addon" type="button" href="<?php echo base_url(); ?>filemanager/dialog.php?type=1&field_id=fieldID"><i class="fa fa-image"></i></a>
											</div>
										</div><!-- end form-group -->

									<?php $hide = (empty($_state->guid)) ? 'hidden' : ''; ?>
										<figure class="thumbnails <?php echo $hide; ?>">
											<?php $src = (!empty($_state->guid)) ? $_state->guid : ''; ?>
											<img src="<?php echo $src; ?>" class="img-responsive thumbnails__img img-thumbnail" alt="" />
											<a href="#" class="thumbnails__link" id="js-remove-avatar"><?php echo $this->lang->line('cms_general_label_remove_featured_image') ?></a>
										</figure><!-- end .thumnails -->
									</div><!-- end content-widget -->

								</div><!-- end wrapper-widget -->

				  		</div><!-- end col-sm-4 -->

				  	</div><!-- end row -->

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->