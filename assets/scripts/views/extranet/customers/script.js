var j = jQuery.noConflict();

(function($){
	j(document).on("ready", function(){
		j('.main__grid__content').load(_root_ + 'extranet/customers/displayAjax');

		j(document).on('click', ".pagination-digg li a", function(e) {
      		e.preventDefault();
      		spinner.spin(target);
    		var href = j(this).attr("href");
    		j('.main__grid__content').load(href);
    		spinner.stop();
  		});

  		j(document).on('click', ".link-ajax", function(e) {
    		e.preventDefault();
    		spinner.spin(target);
    		var href = j(this).attr("href");
    		j('.main__grid__content').load(href);
    		spinner.stop();
  		});

  		j(document).on('click', '#js-customer-search', function(ev){
	  		ev.preventDefault();
	  		var href = j(this).data('href');
	  		var search = j('#js-customer-search-text').val();

	  		if (search.length == 0) {
	  			j('#js-customer-search-text').focus();
	  			return;
	  		}

	  		j('.main__grid__content').load(href + encodeURI(search));
	  	});

	  	j(document).on('keypress', '#js-customer-search-text', function(event){
	  		if (event.which == 13) {
	  			event.preventDefault();
	  			var href = j(this).data('href');
		  		var search = j(this).val();

		  		if (search.length == 0) {
		  			j(this).focus();
		  			return;
		  		}

		  		j('.main__grid__content').load(href + encodeURI(search));
	  		}
	  	});

	  	j('body').on('change', '#js-customer-rows', function(ev) {
      		var value = j(this).val();
      		var href = j(this).data('href');
      		var href_arr = href.split('/');

      		spinner.spin(target);

      		if (value == 'all') {
        		href_new = _root_ + href_arr[4] + '/' + href_arr[5] + '/' + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + 0 + '/' + href_arr[11];
        		//href_new = _root_ + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + href_arr[10] + '/' + href_arr[11] + '/' + 0 + '/' + href_arr[13];
      		} else {
        		href_new = _root_ + href_arr[4] + '/' + href_arr[5] + '/' + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + value + '/' + href_arr[11];
        		//href_new = _root_ + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + href_arr[10] + '/' + href_arr[11] + '/' + value + '/' + href_arr[13];
      		}

      		spinner.stop();
      		j('.main__grid__content').load(href_new);
    	});

		j('#js-frm-add-customer').bootstrapValidator({
	    	// To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
	      	feedbackIcons: {
	        	valid: 'glyphicon glyphicon-ok',
	          	invalid: 'glyphicon glyphicon-remove',
	          	validating: 'glyphicon glyphicon-refresh'
	      	},
	      	live: 'enabled',
	      	fields: {
	      		customer_name: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
	      		customer_email: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				},
	      				emailAddress: {
              				message: 'Email no es válido'
            			}
	      			}
	      		},
	      		customer_contact: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
	      		customer_ruc: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				},
	      				numeric: {
              				message: 'Sólo puede contener valores numéricos'
            			},
            			stringLength: {
              				min: 11,
              				max: 11,
              				message: 'Debe contener 11 caracteres'
            			}
	      			}
	      		}
	      	},
	    })
		.on('success.form.bv', function(e){
			e.preventDefault();
      		spinner.spin(target);

      		var $this = j(this);
      		var $form = j(e.target);
      		var dataArray = $form.serializeArray();

      		var token = dataArray[0].value;
      		var name = dataArray[1].value;
      		var ruc = dataArray[2].value;
      		var email = dataArray[3].value;
      		var contact = dataArray[4].value;
      		var avatar = dataArray[5].value;

      		j.post(_root_ + 'extranet/customers/add', {
        		token 		: token,
        		name     	: name,
        		ruc 		: ruc,
        		email    	: email,
        		contact 	: contact,
        		avatar 		: avatar
      		}, function(data) {
        		spinner.stop();
      			if (data) {
      				j('.main__grid__content').load(_root_ + 'extranet/customers/displayAjax');
      				j('#customer_name').val('');
      				j('#customer_email').val('');
      				j('#customer_contact').val('');
      				jAlert('Se agregó correctamente el cliente.', 'Aviso');
      			} else {
      				jAlert('No se agregó el cliente. Por favor vuelva a intentarlo.', 'Aviso');
      			}
	    	});
		});

		j('#js-frm-edit-customer').bootstrapValidator({
	    	// To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
	      	feedbackIcons: {
	        	valid: 'glyphicon glyphicon-ok',
	          	invalid: 'glyphicon glyphicon-remove',
	          	validating: 'glyphicon glyphicon-refresh'
	      	},
	      	live: 'enabled',
	      	fields: {
	      		customer_name: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
	      		customer_ruc: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				},
	      				numeric: {
              				message: 'Sólo puede contener valores numéricos'
            			},
            			stringLength: {
              				min: 11,
              				max: 11,
              				message: 'Debe contener 11 caracteres'
            			}
	      			}
	      		},
	      		customer_email: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				},
	      				emailAddress: {
              				message: 'Email no es válido'
            			}
	      			}
	      		},
	      		customer_contact: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
	      	},
	    });

	    j('#js-change-pass-customer').on('click', function(ev){
	    	ev.preventDefault();
	    	spinner.spin(target);

	    	var id = j(this).data('id');
	    	var email = j(this).data('email');

	    	j.post(_root_ + 'extranet/customers/changePass', {
	    		id: id,
	    		email: email
	    	}, function(data) {
	    		spinner.stop();
	    		if (data) {
	    			jAlert('Se cambio el password correctamente', 'Aviso');
	    		} else {
	    			jAlert('No se pudo cambiar el password. Por faovor vuelve a intentarlo.', 'Aviso');
	    		}
	    	});
	    })

	    j('body').on('click', '.js-del-customer', function(ev){
	    	ev.preventDefault();
	    	spinner.spin(target);
	    	var $this = j(this);

	    	jConfirm('¿Desea eliminar el registro?', 'Confirmar eliminación', function(r){
	    		if (r) {
			    	var id = $this.data('id');

			    	j.post(_root_ + 'extranet/customers/delete', {
			    		id: id
			    	}, function(data) {
			    		if (data) {
			    			jAlert('Se eliminó correctamente el registro', 'Aviso');
			    			j('.main__grid__content').load(_root_ + 'extranet/customers/displayAjax');
			    		} else {
			    			jAlert('No se pudo eliminar el registro. Por favor vuelva a intentarlo', 'Aviso');
			    		}
			    	});
	    		}

	    		spinner.stop();
	    	});
	    });

	    j('.iframe-btn').fancybox({
      		'width' : 880,
      		'minHeight' : 500,
      		'type' : 'iframe',
      		'autoScale' : false,
      		'topRatio' : 0.3,
      		beforeClose : function() {
        		imgUrl = j('#fieldID').val();

        		if (imgUrl.length > 0) {
        			figure = j('.thumbnails');
	        		if (figure.hasClass('hidden')) {
	          			figure.removeClass('hidden');
	        		}
	        		figure.find('img').attr('src', imgUrl);
        		}
      		}
    	});

    	// Quitar imagen destacada al momento de agregar plantilla
    	j('#js-remove-avatar').on('click', function(ev){
      		ev.preventDefault();
      		var figure = j('.thumbnails');
      		j('#fieldID').val('');
      		figure.find('img').attr('src', '').parent().addClass('hidden');
      		j('.iframe-btn').removeClass('hidden');
    	});
	});
})(jQuery);