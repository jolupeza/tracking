<div class="row">
	<div class="col-xs-12">
		<header class="main__header">
			<h2 class="main__header__title h3"><?php echo $this->lang->line('cms_general_title_edit_customer'); ?></h2>
			<div class="main__header__buttons">
				<!--button class="button button--blue"><?php //echo $this->lang->line('cms_general_label_add_new'); ?></button-->
				<a href="<?php echo base_url(); ?>extranet/customers" class="button button--red"><?php echo $this->lang->line('cms_general_label_view_all'); ?></a>
			</div><!-- end main__header__buttons -->
			<hr class="hr" />
		</header><!-- end main__header -->
	</div><!-- end col-xs-12 -->
</div><!-- end row -->
<div class="row">
	<div class="col-xs-12">
		<section class="main__content">
			<?php if (validation_errors()) : ?>
			<div class="alert alert-danger">
			    <?php echo validation_errors('<p>', '</p>'); ?>
			</div>
			<?php endif; ?>

			<?php echo form_open('', array('id' => 'js-frm-edit-customer'), array('token' => $_token)); ?>
			<div class="row">
				<div class="col-xs-8">
					<!-- Customer Name -->
					<div class="form-group">
	    				<label for="customer_name" class="form__label"><?php echo $this->lang->line('cms_general_title_name_customer'); ?></label>
	    				<input type="text" class="form-control form__input" name="customer_name" id="customer_name" value="<?php echo $_customer->name; ?>"  required/>
	  				</div><!-- end form-group -->

	  				<!-- Customer RUC -->
					<div class="form-group">
						<label for="customer_ruc" class="form__label">RUC</label>
						<input type="text" class="form-control form__input" name="customer_ruc" id="customer_ruc" value="<?php echo $_customer->user; ?>" required />
					</div><!-- end form-group -->

	  				<!-- Customer Email -->
					<div class="form-group">
	    				<label for="customer_email" class="form__label"><?php echo $this->lang->line('cms_general_label_email'); ?></label>
	    				<input type="email" class="form-control form__input" name="customer_email" id="customer_email" value="<?php echo $_customer->email; ?>"  required/>
	  				</div><!-- end form-group -->

	  				<!-- Customer Contact -->
					<div class="form-group">
	    				<label for="customer_contact" class="form__label"><?php echo $this->lang->line('cms_general_title_name_contact'); ?></label>
	    				<?php $meta = unserialize($_customer->meta_value); ?>
	    				<input type="text" class="form-control form__input" name="customer_contact" id="customer_contact" value="<?php echo $meta['contact']; ?>"  required/>
	  				</div><!-- end form-group -->

					<button type="submit" class="button button--default"><?php echo $this->lang->line('cms_general_label_save'); ?></button>
				</div><!-- end col-xs-8 -->
				<div class="col-xs-4">
					<section class="widget text-right">
						<h4 class="widget__title text-left"><?php echo $this->lang->line('cms_general_change_password'); ?> <span class="tool" data-toggle="tooltip" data-placement="bottom" title="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus."></span></h4>

						<button type="submit" class="button button--default" id="js-change-pass-customer" data-id="<?php echo $_customer->id; ?>" data-email="<?php echo $_customer->email; ?>"><?php echo $this->lang->line('cms_general_change_password'); ?></button>
					</section><!-- end widget -->

					<section class="widget text-right">
						<h4 class="widget__title text-left"><?php echo $this->lang->line('cms_general_label_featured_image'); ?> <span class="tool" data-toggle="tooltip" data-placement="bottom" title="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus."></span></h4>

						<!-- Customer Avatar -->
						<div class="form-group">
							<div class="input-group">
							  	<input type="text" class="form-control form__input" id="fieldID" name="customer_avatar" id="customer_avatar" value="<?php echo $_customer->avatar; ?>" placeholder="<?php echo $this->lang->line('cms_general_label_avatar'); ?>">
							  	<a class="btn iframe-btn input-group-addon" type="button" href="<?php echo base_url(); ?>filemanager/dialog.php?type=1&field_id=fieldID"><i class="fa fa-image"></i></a>
							</div>
						</div><!-- end form-group -->

					<?php $hide = (!empty($_customer->avatar)) ? '' : 'hidden'; ?>
					<?php $avatar = (!empty($_customer->avatar)) ? $_customer->avatar : ''; ?>
						<figure class="thumbnails <?php echo $hide; ?>">
							<img src="<?php echo $avatar; ?>" class="img-responsive thumbnails__img img-thumbnail" alt="" />
							<a href="#" class="thumbnails__link" id="js-remove-avatar"><?php echo $this->lang->line('cms_general_label_remove_featured_image') ?></a>
						</figure><!-- end .thumnails -->
					</section><!-- end widget -->
				</div><!-- end col-xs-4 -->
			</div><!-- end row -->
			<?php echo form_close(); ?>
		</section><!-- end main__content -->
	</div><!-- end col-xs-12 -->
</div><!-- end row -->