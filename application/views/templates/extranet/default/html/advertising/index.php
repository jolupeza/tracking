<div class="row">
	<div class="col-xs-12">
		<header class="main__header">
			<h2 class="main__header__title h3">Publicidad</h2>
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
			<?php if ($this->user->has_permission('add_advertising')) : ?>
				<section class="widget">
					<h4 class="widget__title">Crear publicidad</h4><!-- end widget__title -->

					<?php echo form_open('', array('id' => 'js-frm-add-publi'), array('token' => $_token)); ?>

						<!-- Publicidad Name -->
						<div class="form-group">
							<label for="publi_name" class="sr-only">Nombre</label>
							<input type="text" class="form-control form__input" name="publi_name" id="publi_name" placeholder="Nombre" required />
						</div><!-- end form-group -->

						<?php if ($this->user->has_permission('upload_files')) : ?>
						<!-- Customer Avatar -->
						<div class="form-group">
							<div class="input-group">
							  	<input type="text" class="form-control form__input" id="fieldID" name="publi_avatar" value="" placeholder="Imagen">
							  	<a class="btn iframe-btn input-group-addon" type="button" href="<?php echo base_url(); ?>filemanager/dialog.php?type=1&field_id=fieldID"><i class="fa fa-image"></i></a>
							</div>
						</div><!-- end form-group -->

						<figure class="thumbnails hidden" id="js-wrapper-avatar">
							<img src="" class="img-responsive thumbnails__img img-thumbnail" alt="" />
							<a href="#" class="thumbnails__link" id="js-remove-avatar">Quitar imagen</a>
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