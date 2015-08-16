var j = jQuery.noConflict();

(function($){
	var $body = j('body');

	var StateDates = [];

	j(document).on("ready", function(){
		j('input[id^="state-date-"]').each(function(index, el) {
			var id = j(this).attr('id');
			id = id.split('-');
			id = id[id.length - 1];
			StateDates.push(id);
		});

		j('#dtp-orderdate').datetimepicker({
			defaultDate: (j("#dtp-orderdate").data('datetime')) ? moment(j("#dtp-orderdate").data('datetime')) : '',
			locale: 'es'
	    });

	    j('#dtp-deliverydate').datetimepicker({
			defaultDate: (j("#dtp-deliverydate").data('datetime')) ? moment(j("#dtp-deliverydate").data('datetime')) : '',
			locale: 'es'
	    });

	    j('#js-frm-order').bootstrapValidator({
	    	// To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
	      	feedbackIcons: {
	        	valid: 'glyphicon glyphicon-ok',
	          	invalid: 'glyphicon glyphicon-remove',
	          	validating: 'glyphicon glyphicon-refresh'
	      	},
	      	live: 'enabled',
	      	fields: {
	      		order_title: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
	      		order_detail: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
	      		order_site: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
	      		order_customer: {
          			validators: {
          				callback: {
              				message: 'Seleccionar cliente',
              				callback: function(value, validator) {
	                			if (value == '0') {
	                  				return false;
	                			}

	                			return true;
              				}
            			}
          			}
        		},
        		order_date: {
          			validators: {
            			notEmpty: {
              				message: 'Debe indicar fecha'
            			},
	            		date: {
	              			format: 'YYYY-MM-DD',
	              			message: 'Fecha no válida',
	            		},
	            		callback: {
	              			message: 'Indicar fecha mayor o igual a la fecha actual y menor a la fecha de entrega',
	              			callback: function(value, validator, $field) {
	              				//console.log($field.data('edit'));
	                			var m = new moment(value, 'YYYY-MM-DD', true);
	                			var now = moment().format('YYYY-MM-DD');
	                			var dateEnd = moment(j('#dtp-deliverydate').data('DateTimePicker').date()).format('YYYY-MM-DD');

	                			if (dateEnd !== 'Invalid date') {
	                				if (m.unix() > moment(dateEnd).unix()) {
	                					return false;
	                				}
	                			}

	                			if (!m.isValid()) {
	                  				return false;
	                			}

	                			if ($field.data('edit') === 'undefined')
	                			{
		                			if (m.unix() < moment(now).unix()) {
		                  				return false;
		                			}
	                			}

	                			//return m.isAfter(moment());
	                			return true;
	              			}
	            		}
	          		},
	          		onSuccess: function(e, data) {
	          			var date = data.element[0].value;
	          			j('.main__content__observation__article').first().find('input[id^="order_obs_date"]').val(date);
	          			j('div[id^="dtp-date-state"]').each(function(index, el) {
	          				j(this).data("DateTimePicker").minDate(date);
	          			});
	          		},
	          		onError: function(e, data) {
	          			j('.main__content__observation__article').first().find('input[id^="order_obs_date"]').val('');
	          			j('div[id^="dtp-date-state"]').each(function(index, el) {
	          				j(this).data("DateTimePicker").minDate(false);
	          			});
	          		}
        		},
        		order_deliverydate: {
          			validators: {
            			notEmpty: {
              				message: 'Debe indicar fecha'
            			},
	            		date: {
	              			format: 'YYYY-MM-DD',
	              			message: 'Fecha no válida',
	            		},
	            		callback: {
	              			message: 'Indicar fecha mayor o igual a la fecha de pedido',
	              			callback: function(value, validator) {
	                			var m = new moment(value, 'YYYY-MM-DD', true);
	                			var now = moment().format('YYYY-MM-DD');
	                			var dateStart = moment(j('#dtp-orderdate').data('DateTimePicker').date()).format('YYYY-MM-DD');

	                			if (dateStart !== 'Invalid date') {
	                				if (m.unix() < moment(dateStart).unix()) {
	                					return false;
	                				}
	                			}

	                			if (!m.isValid()) {
	                  				return false;
	                			}

	                			if (m.unix() < moment(now).unix()) {
	                  				return false;
	                			}

	                			//return m.isAfter(moment());
	                			return true;
	              			}
	            		}
	          		},
	          		onSuccess: function(e, data) {
	          			var date = data.element[0].value;
	          			j('.main__content__observation__article').last().find('div[id^="dtp-date-state-"]').data("DateTimePicker").date(date);
	          			j('div[id^="dtp-date-state"]').each(function(index, el) {
	          				j(this).data("DateTimePicker").maxDate(date);
	          			});
	          		},
	          		onError: function(e, data) {
	          			j('.main__content__observation__article').last().find('input[id^="state-date-"]').val('');
	          			j('div[id^="dtp-date-state"]').each(function(index, el) {
	          				j(this).data("DateTimePicker").maxDate(false);
	          			});
	          		}
        		}
	      	},
	    })
		.on('success.field.bv', function(e, data){
      		data.fv.disableSubmitButtons(false);
	    });

	    j('#dtp-orderdate').on('dp.change dp.show', function(e) {
      		revalidate('order_date', 'js-frm-order');
      		revalidate('order_deliverydate', 'js-frm-order');
    	});

    	j('#dtp-deliverydate').on('dp.change dp.show', function(e) {
      		revalidate('order_deliverydate', 'js-frm-order');
      		revalidate('order_date', 'js-frm-order');
    	});

    	j('#order_date').on('focusout', function(){
      		j('#js-frm-order').formValidation('revalidateField', 'order_date');
      		j('#js-frm-order').formValidation('revalidateField', 'order_deliverydate');
    	});

    	j('#order_deliverydate').on('focusout', function(){
      		j('#js-frm-order').formValidation('revalidateField', 'order_deliverydate');
      		j('#js-frm-order').formValidation('revalidateField', 'order_date');
    	});

    	StateDates.forEach(addDateState);
    	StateDates.forEach(loadDateTimePicker);

    	function addDateState(element, index, array) {
    		j('#dtp-date-state-' + element).on('dp.change dp.show', function(e){
    			var date = moment(j('#state-date-' + element).val()).format('YYYY-MM-DD');
    			j('#order_obs_date_' + array[index + 1]).val(date);
    		});
    	}

    	function loadDateTimePicker(element, index, array) {
    		j('#dtp-date-state-' + element).datetimepicker({
				defaultDate: (j("#dtp-date-state-" + element).data('datetime')) ? moment(j("#dtp-date-state-" + element).data('datetime')) : ''
		    });

  			if (j('#dtp-orderdate').data('datetime')) {
  				var date = j('#dtp-orderdate').data('datetime');
  				j('#dtp-date-state-' + element).data("DateTimePicker").minDate(date);
  			}

  			if (j('#dtp-deliverydate').data('datetime')) {
  				var date = j('#dtp-deliverydate').data('datetime');
  				j('#dtp-date-state-' + element).data("DateTimePicker").maxDate(date);
  			}
    	}
	});
})(jQuery);

function revalidate(field, form) {
  	j('#' + form).formValidation('revalidateField', field);
}