<div class="row">
	<div class="col-xs-12">
		<header class="main__header">
			<h2 class="main__header__title h3">Editar publicidad</h2>
			<div class="main__header__buttons">
				<!--button class="button button--blue"><?php //echo $this->lang->line('cms_general_label_add_new'); ?></button-->
				<a href="<?php echo base_url(); ?>extranet/advertising" class="button button--red"><?php echo $this->lang->line('cms_general_label_view_all'); ?></a>
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

			<?php echo form_open('', array('id' => 'js-frm-edit-publi'), array('token' => $_token)); ?>
			<div class="row">
				<div class="col-xs-8">
					<!-- Customer Name -->
					<div class="form-group">
	    				<label for="publi_name" class="form__label">Nombre</label>
	    				<input type="text" class="form-control form__input" name="publi_name" id="publi_name" value="<?php echo $_advertising->post_title; ?>"  required/>
	  				</div><!-- end form-group -->

	  				<button type="submit" class="button button--default"><?php echo $this->lang->line('cms_general_label_save'); ?></button>
				</div><!-- end col-xs-8 -->
				<div class="col-xs-4">
					<section class="widget text-right">
						<h4 class="widget__title text-left">Imagen</h4>

						<!-- Customer Avatar -->
						<div class="form-group">
							<div class="input-group">
							  	<input type="text" class="form-control form__input" id="fieldID" name="publi_avatar" value="<?php echo $_advertising->guid; ?>" />
							  	<a class="btn iframe-btn input-group-addon" type="button" href="<?php echo base_url(); ?>filemanager/dialog.php?type=1&field_id=fieldID"><i class="fa fa-image"></i></a>
							</div>
						</div><!-- end form-group -->

					<?php $hide = (!empty($_advertising->guid)) ? '' : 'hidden'; ?>
					<?php $avatar = (!empty($_advertising->guid)) ? $_advertising->guid : ''; ?>
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