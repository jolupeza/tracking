<div class="row">
	<div class="col-xs-12">
		<header class="main__header">
			<h2 class="main__header__title h3"><?php echo $this->lang->line('cms_general_title_customers'); ?></h2>
			<hr class="hr" />
		</header>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<section class="main__grid">
			<div class="col-xs-9">
				<article class="main__grid__content"></article><!-- end main__grid__content -->
			</div><!-- end col-xs-9 -->
			<div class="col-xs-3">
			<?php if ($this->user->has_permission('add_customers')) : ?>
				<section class="widget">
					<h4 class="widget__title"><?php echo $this->lang->line('cms_general_title_add_customer'); ?></h4><!-- end widget__title -->

					<?php echo form_open('', array('id' => 'js-frm-add-customer'), array('token' => $_token)); ?>

						<!-- Customer Name -->
						<div class="form-group">
							<label for="customer_name" class="sr-only"><?php echo $this->lang->line('cms_general_title_name_customer'); ?></label>
							<input type="text" class="form-control form__input" name="customer_name" id="customer_name" placeholder="<?php echo $this->lang->line('cms_general_title_name_customer'); ?>" required />
						</div><!-- end form-group -->

						<!-- Customer RUC -->
						<div class="form-group">
							<label for="customer_ruc" class="sr-only">RUC</label>
							<input type="text" class="form-control form__input" name="customer_ruc" id="customer_ruc" placeholder="RUC" required />
						</div><!-- end form-group -->

						<!-- Customer Email -->
						<div class="form-group">
							<label for="customer_email" class="sr-only"><?php echo $this->lang->line('cms_general_label_email'); ?></label>
							<input type="email" class="form-control form__input" name="customer_email" id="customer_email" placeholder="<?php echo $this->lang->line('cms_general_label_email'); ?>" required />
						</div><!-- end form-group -->

						<!-- Customer Contact -->
						<div class="form-group">
							<label for="customer_contact" class="sr-only"><?php echo $this->lang->line('cms_general_title_name_contact'); ?></label>
							<input type="text" class="form-control form__input" name="customer_contact" id="customer_contact" placeholder="<?php echo $this->lang->line('cms_general_title_name_contact'); ?>" required />
						</div><!-- end form-group -->

						<?php if ($this->user->has_permission('upload_files')) : ?>
						<!-- Customer Avatar -->
						<div class="form-group">
							<div class="input-group">
							  	<input type="text" class="form-control form__input" id="fieldID" name="customer_avatar" id="customer_avatar" value="" placeholder="<?php echo $this->lang->line('cms_general_label_avatar'); ?>">
							  	<a class="btn iframe-btn input-group-addon" type="button" href="<?php echo base_url(); ?>filemanager/dialog.php?type=1&field_id=fieldID"><i class="fa fa-image"></i></a>
							</div>
						</div><!-- end form-group -->

						<figure class="thumbnails hidden">
							<img src="" class="img-responsive thumbnails__img img-thumbnail" alt="" />
							<a href="#" class="thumbnails__link" id="js-remove-avatar"><?php echo $this->lang->line('cms_general_label_remove_featured_image') ?></a>
						</figure><!-- end .thumnails -->
						<?php endif; ?>

						<p class="text-center">
							<button type="submit" class="button button--red"><?php echo $this->lang->line('cms_general_label_add_new'); ?></button>
						</p>

					<?php echo form_close(); ?>
				</section><!-- end widget -->
			<?php endif; ?>
			</div><!-- end-xs-3 -->
		</section>
	</div>
</div>