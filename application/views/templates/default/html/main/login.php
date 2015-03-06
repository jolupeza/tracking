<?php if (validation_errors()) : ?>
<div class="alert alert-danger">
    <?php echo validation_errors('<p>', '</p>'); ?>
</div>
<?php endif; ?>

<section class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title">Iniciar sesión</h3>
	</div>

	<div class="panel-body">
		<?php echo form_open('', array('id' => 'form_login', 'class' => 'form-horizontal frm_login'), array('token' => $_token)); ?>
			<div class="form-group">
				<label for="login_email" class="col-sm-2 control-label"><?php echo $this->lang->line('cms_general_label_email'); ?></label>
				<div class="col-sm-10">
					<input type="email" name="login_email" id="login_email" class="form-control" value="<?php echo set_value('login_email'); ?>" placeholder="<?php echo $this->lang->line('cms_general_label_email'); ?>" />
				</div><!-- end col-sm-10 -->
			</div><!-- end form-group -->

			<div class="form-group">
				<label for="login_pass" class="col-sm-2 control-label"><?php echo $this->lang->line('cms_general_label_password'); ?></label>
				<div class="col-sm-10">
					<input type="password" name="login_pass" id="login_pass" class="form-control" placeholder="<?php echo $this->lang->line('cms_general_label_password'); ?>" />
				</div><!-- end col-sm-10 -->
			</div><!-- end form-group -->

			<div class="form-group">
    			<div class="col-sm-offset-2 col-sm-10 text-right">
      				<button type="submit" class="btn btn-default">Ingresar</button>
    			</div>
  			</div>
		<?php echo form_close(); ?>
	</div>
</section>