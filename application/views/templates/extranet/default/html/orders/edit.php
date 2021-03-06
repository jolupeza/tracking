<div class="row">
	<div class="col-xs-12">
		<header class="main__header">
			<h2 class="main__header__title h3"><?php echo $this->lang->line('cms_general_title_orders'); ?></h2>
			<div class="main__header__buttons">
			<?php if ($this->user->has_permission('add_orders')) : ?>
				<a href="<?php echo base_url(); ?>extranet/orders" class="button button--blue"><?php echo $this->lang->line('cms_general_label_add_new'); ?></a>
			<?php endif; ?>
			<?php if ($this->user->has_permission('view_orders')) : ?>
				<a href="<?php echo base_url(); ?>extranet/main" class="button button--red"><?php echo $this->lang->line('cms_general_label_view_all'); ?></a>
			<?php endif; ?>
			</div><!-- end main__header__buttons -->
			<hr class="hr" />
		</header><!-- end main__header -->
	</div><!-- end col-xs-12 -->
</div><!-- end row -->
<div class="row">
	<div class="col-xs-12">
		<section class="main__content">
			<h3 class="h4 main__content__title"><?php echo $this->lang->line('cms_general_title_edit_orders'); ?></h3>

		<?php if (isset($_order)) : ?>
			<?php if (validation_errors()) : ?>
			<div class="alert alert-danger">
			    <?php echo validation_errors('<p>', '</p>'); ?>
			</div>
			<?php endif; ?>

			<?php echo form_open('', array('id' => 'js-frm-order'), array('token' => $_token)); ?>
			<div class="row">
				<div class="col-xs-8">
					<!-- Order Title -->
					<div class="form-group">
	    				<label for="order_title" class="sr-only"><?php echo $this->lang->line('cms_general_title_order'); ?></label>
	    				<input type="text" class="form-control form__input" name="order_title" id="order_title" placeholder="<?php echo $this->lang->line('cms_general_title_order'); ?>" value="<?php echo $_order->post_title; ?>"  required/>
	  				</div><!-- end form-group -->

					<!-- Order Detail -->
	  				<div class="form-group">
	    				<label for="order_detail" class="form__label"><?php echo $this->lang->line('cms_general_title_order_detail'); ?></label>
	    				<textarea class="form-control form__input" name="order_detail" id="order_detail" rows="8" required><?php echo $_order->post_content; ?></textarea>
	  				</div><!-- end form-group -->

	  			<?php if (isset($_states)) : ?>
					<?php $z = 1; $totalStates = count($_states); ?>
	  				<h4>Observaciones</h4>
	  				<section class="main__content__observation">
	  				<?php foreach ($_states as $state) : ?>
	  					<article class="main__content__observation__article">
	  						<div class="main__content__observation__date">
								<div class="main__content__observation__date__start">
									<?php $value = (!empty($_obs[$state->id][1])) ? 'value="' . $_obs[$state->id][1] . '"' : ''; ?>
									<label for="order_date" class="form__label"><?php echo $this->lang->line('cms_general_label_date_start'); ?></label>
	  								<input type="text" class="form-control form__input" name="order_obs_date[<?php echo $state->id; ?>]" id="order_obs_date_<?php echo $state->id; ?>" <?php echo $value; ?> readonly />
								</div><!-- end main__content_observation__date__start -->
								<?php $readonly = ($z == $totalStates) ? 'readonly' : ''; ?>
								<div class="main__content__observation__date__end">
			  						<!-- State date -->
									<div class="form-group">
							  			<label for="order_date" class="form__label"><?php echo $this->lang->line('cms_general_label_date_end'); ?></label>
							  			<?php
							  				$stateDate = ($_obs[$state->id][2] != '') ? 'data-datetime="' . $_obs[$state->id][2] . '"' : '';
							  			?>
										<div class="input-group form__date" id="dtp-date-state-<?php echo $state->id; ?>" data-date-format="YYYY-MM-DD" <?php echo $stateDate; ?>>
											<input type="text" class="form-control" name="state-date[<?php echo $state->id; ?>]" id="state-date-<?php echo $state->id; ?>" placeholder="AAAA-MM-DD" <?php echo $readonly; ?> />
											<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										</div>
									</div><!-- end form-group -->
								</div><!-- end main__content__observation__date__end -->
	  						</div><!-- end .main__content__observation__date -->
	  						<h4 class="main__content__observation__article__title"><?php echo $state->post_title; ?></h4><!-- end main__content__observation__title -->
	  						<?php $textarea = ($_obs[$state->id][0] != '') ? $_obs[$state->id][0] : ''; ?>
	  						<textarea class="main__content__observation__article__textarea form__textarea" name="order_obs_state[<?php echo $state->id; ?>]" placeholder="Ingresar observación aquí"><?php echo $textarea; ?></textarea>
	  					</article><!-- end main__content__observation -->
	  					<?php $z++; ?>
	  				<?php endforeach; ?>
	  				</section>
	  			<?php endif; ?>

				<?php if ($_order->post_status === 'initiated') : ?>
					<button type="submit" class="button button--default"><?php echo $this->lang->line('cms_general_label_save'); ?></button>
				<?php endif; ?>

				</div><!-- end col-xs-8 -->
				<div class="col-xs-4">
					<section class="widget">
						<h4 class="widget__title"><?php echo $this->lang->line('cms_general_label_status'); ?> <span class="tool" data-toggle="tooltip" data-placement="bottom" title="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus."></span></h4>
						<div class="widget__chekbox">
							<!-- Order Status -->
							<div class="widget__checkbox__group">
								<?php $checked = ($_order->post_status === 'initiated') ? 'checked="checked"' : ''; ?>
								<input type="radio" name="order_status" id="order_status_start" value="1" <?php echo $checked; ?> /><span class="lbl"><?php echo $this->lang->line('cms_general_label_initiated'); ?></span>
							</div><!-- end .widget__checkbox__group -->
							<div class="widget__checkbox__group">
								<?php $checked = ($_order->post_status === 'finalized') ? 'checked="checked"' : ''; ?>
								<input type="radio" name="order_status" id="order_status_end" value="2" <?php echo $checked; ?> /><span class="lbl"><?php echo $this->lang->line('cms_general_label_finalized'); ?></span>
							</div><!-- end .widget__checkbox__group -->
						</div><!-- end widget__checkbox -->
					</section><!-- end widget -->

					<section class="widget">
						<h4 class="widget__title"><?php echo $this->lang->line('cms_general_label_additional_data'); ?> <span class="tool" data-toggle="tooltip" data-placement="bottom" title="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus."></span></h4>
						<!-- Order Site -->
						<div class="form-group">
							<label for="order_site" class="sr-only"><?php echo $this->lang->line('cms_general_title_destination'); ?></label>
							<input type="text" class="form-control form__input" name="order_site" id="order_site" placeholder="<?php echo $this->lang->line('cms_general_title_destination'); ?>" value="<?php echo $_order->post_excerpt; ?>" required />
						</div><!-- end form-group -->

					<?php if (isset($_customers)) : ?>
						<!-- Order Customer -->
						<div class="form-group">
							<label for="order_customer" class="sr-only">Seleccione cliente</label>
							<select name="order_customer" id="order_customer" class="form-control form__select">
								<option value="0">-- Selecciona un cliente --</option>
							<?php foreach ($_customers as $customer) : ?>
								<?php $selected = ($customer->id === $_order->post_author) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo $customer->id ?>" <?php echo $selected; ?>><?php echo $customer->name; ?></option>
							<?php endforeach; ?>
							</select><!-- end form__select -->
						</div><!-- end form-group -->
					<?php endif; ?>
					</section><!-- end widget -->

					<section class="widget">
						<h4 class="widget__title"><?php echo $this->lang->line('cms_general_label_date'); ?> <span class="tool" data-toggle="tooltip" data-placement="bottom" title="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus."></span></h4>
						<!-- Order date -->
						<div class="form-group">
				  			<label for="order_date" class="form__label"><?php echo $this->lang->line('cms_general_title_order_date'); ?></label>
							<div class="input-group form__date" id="dtp-orderdate" data-date-format="YYYY-MM-DD" data-datetime="<?php echo date('Y-m-d', strtotime($_order->created_at)); ?>">
								<input type="text" class="form-control" name="order_date" id="order_date" placeholder="AAAA-MM-DD" required data-edit="1" />
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div><!-- end form-group -->

						<!-- Delivery Date -->
						<div class="form-group">
				  			<label for="order_deliverydate" class="form__label"><?php echo $this->lang->line('cms_general_title_delivery_date'); ?></label>
							<div class="input-group form__date" id="dtp-deliverydate" data-date-format="YYYY-MM-DD" data-datetime="<?php echo date('Y-m-d', strtotime($_order->published_at)); ?>">
								<input type="text" class="form-control" name="order_deliverydate" id="order_deliverydate" placeholder="AAAA-MM-DD" required />
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
					</section><!-- end widget -->
				</div><!-- end col-xs-4 -->
			</div><!-- end row -->
			<?php echo form_close(); ?>
		<?php endif; ?>
		</section><!-- end main__content -->
	</div><!-- end col-xs-12 -->
</div><!-- end row -->