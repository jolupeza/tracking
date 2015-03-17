var j = jQuery.noConflict();

(function($){
	var $body = j('body');

	j(document).on("ready", function(){
		j('.main__grid__content').load(_root_ + 'extranet/advertising/displayAjax');

		// Activamos el switch
	  	j("[name='publi_status']").bootstrapSwitch({
	  		size: 			'small',
	  		onColor: 		'success',
	  		radioAllOff: 	true
	  	});

		j(document).on('click', ".pagination-digg li a", function(event) {
      		event.preventDefault();
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

  		j(document).on('click', '#js-advertising-search', function(ev){
	  		ev.preventDefault();
	  		var href = j(this).data('href');
	  		var search = j('#js-advertising-search-text').val();

	  		if (search.length == 0) {
	  			j('#js-advertising-search-text').focus();
	  			return;
	  		}

	  		j('.main__grid__content').load(href + encodeURI(search));
	  	});

	  	j(document).on('keypress', '#js-advertising-search-text', function(event){
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

	  	$body.on('change', '#js-advertising-rows', function(ev) {
      		var value = j(this).val();
      		var href = j(this).data('href');
      		var href_arr = href.split('/');

      		spinner.spin(target);

      		if (value == 'all') {
        		href_new = _root_ + href_arr[3] + '/' + href_arr[4] + '/' + href_arr[5] + '/' + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + 0 + '/' + href_arr[10];
        		//href_new = _root_ + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + href_arr[10] + '/' + href_arr[11] + '/' + 0 + '/' + href_arr[13];
      		} else {
        		href_new = _root_ + href_arr[3] + '/' + href_arr[4] + '/' + href_arr[5] + '/' + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + value + '/' + href_arr[10];
        		//href_new = _root_ + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + href_arr[10] + '/' + href_arr[11] + '/' + value + '/' + href_arr[13];
      		}

      		spinner.stop();
      		j('.main__grid__content').load(href_new);
    	});

		j('#js-frm-add-publi').bootstrapValidator({
	    	// To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
	      	feedbackIcons: {
	        	valid: 'glyphicon glyphicon-ok',
	          	invalid: 'glyphicon glyphicon-remove',
	          	validating: 'glyphicon glyphicon-refresh'
	      	},
	      	live: 'enabled',
	      	fields: {
	      		publi_name: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
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
      		var avatar = dataArray[2].value;

      		j.post(_root_ + 'extranet/advertising/add', {
				token 		: token,
				name     	: name,
				avatar 		: avatar
      		}, function(data) {
        		spinner.stop();

      			if (data.valid) {
      				j('.main__grid__content').load(_root_ + 'extranet/advertising/displayAjax');
      				j('#publi_name').val('');
      				j('#fieldID').val('');
      				j('#js-wrapper-avatar').addClass('hidden').find('img').attr('src', '');
      				j('#js-frm-add-publi').bootstrapValidator('resetForm', true);
      				jAlert('Se agregó correctamente la publicidad.', 'Aviso');
      			} else {
      				jAlert('No se agregó la publicidad. Por favor vuelva a intentarlo.', 'Aviso');
      			}
	    	}, 'json');
		});

		j('#js-frm-edit-publi').bootstrapValidator({
	    	// To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
	      	feedbackIcons: {
	        	valid: 'glyphicon glyphicon-ok',
	          	invalid: 'glyphicon glyphicon-remove',
	          	validating: 'glyphicon glyphicon-refresh'
	      	},
	      	live: 'enabled',
	      	fields: {
	      		publi_name: {
	      			validators: {
	      				notEmpty: {
	      					message: 'Campo requerido'
	      				}
	      			}
	      		},
	      	},
	    });

	    $body.on('click', '.js-del-advertising', function(ev){
	    	ev.preventDefault();
	    	spinner.spin(target);
	    	var $this = j(this);

	    	jConfirm('¿Desea eliminar el registro?', 'Confirmar eliminación', function(r){
	    		if (r) {
			    	var id = $this.data('id');

			    	j.post(_root_ + 'extranet/advertising/delete', {
			    		id: id
			    	}, function(data) {
			    		if (data.result) {
			    			jAlert('Se eliminó correctamente el registro', 'Aviso');
			    			j('.main__grid__content').load(_root_ + 'extranet/advertising/displayAjax');
			    		} else {
			    			jAlert('No se pudo eliminar el registro. Por favor vuelva a intentarlo', 'Aviso');
			    		}
			    	}, 'json');
	    		}

	    		spinner.stop();
	    	});
	    });

	    $body.on('click', '#js-public-active', function(ev) {
	    	spinner.spin(target);
	    	var $this = j(this);
	    	var id = $this.val();

	    	$.post(_root_ + 'extranet/advertising/activePubli', {
	    		id: id
	    	}, function(data) {
	    		spinner.stop();

	    		if (data.result)
	    		{
	    			j('.main__grid__content').load(_root_ + 'extranet/advertising/displayAjax');
	    			jAlert('Se cambió la publicidad activa.', 'Aviso');
	    		}
	    		else
	    		{
      				jAlert('No se pudo cambiar la publicidad activa. Por favor vuelva a intentarlo.', 'Aviso');
	    		}
	    	}, 'json');
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