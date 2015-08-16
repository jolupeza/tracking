<div class="row">
	<div class="col-xs-12">
		<main class="login">
			<h1 class="login__logo text-center">
				<img src="<?php echo base_url(); ?>assets/images/shanoc-logo.png" alt="Shanoc" class="img-responsive login__logo__img" />
			</h1>

			<?php if (validation_errors()) : ?>
			<div class="alert alert-danger">
			    <?php echo validation_errors('<p>', '</p>'); ?>
			</div>
			<?php endif; ?>

			<?php echo form_open('', array('id' => 'login__form', 'class' => 'login__form'), array('token' => $_token)); ?>
				<div class="form-group login__form__group">
					<label for="login_email" class="login__form__label sr-only"><?php echo $this->lang->line('cms_general_label_email'); ?></label>
					<input type="email" name="login_email" id="login_email" class="form-control login__form__input" value="<?php echo set_value('login_email'); ?>" placeholder="<?php echo $this->lang->line('cms_general_label_email'); ?>" />
					<span class="icon-user-tie login__form__group__icon"></span>
				</div><!-- end form-group -->

				<div class="form-group login__form__group">
					<label for="login_pass" class="login__form__label sr-only"><?php echo $this->lang->line('cms_general_label_password'); ?></label>
					<input type="password" name="login_pass" id="login_pass" class="form-control login__form__input" placeholder="<?php echo $this->lang->line('cms_general_label_password'); ?>" />
					<span class="icon-locked login__form__group__icon"></span>
				</div><!-- end form-group -->

				<div class="form-group login__form__group text-right">
	      			<button type="submit" class="button button--default">Ingresar</button>
	  			</div>
			<?php echo form_close(); ?>

			<p class="text-center">
				<a href="<?php echo $this->config->item('web-shanoc'); ?>" class="link-big"><< Regresar al inicio</a>
			</p>
		</main>
	</div>
</div>