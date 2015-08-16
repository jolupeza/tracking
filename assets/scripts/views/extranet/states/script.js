var j = jQuery.noConflict();

(function($){
	var $body = j('body');

	j(document).on("ready", function(){
		j('.main__grid__content').load(_root_ + 'extranet/states/displayAjax');

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

  		j(document).on('click', '#js-state-search', function(ev){
	  		ev.preventDefault();
	  		var href = j(this).data('href');
	  		var search = j('#js-state-search-text').val();

	  		if (search.length == 0) {
	  			j('#js-state-search-text').focus();
	  			return;
	  		}

	  		j('.main__grid__content').load(href + encodeURI(search));
	  	});

	  	j(document).on('keypress', '#js-state-search-text', function(event){
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

	  	$body.on('change', '#js-state-rows', function(ev) {
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

		$body.on('click', '.js-change-order-down', function(event){
			changeOrder(event, j(this), 'down');
		});

		$body.on('click', '.js-change-order-up', function(event){
			changeOrder(event, j(this), 'up');
		});

		$body.on('dblclick', '.js-change-name-state', function(){
			var $this = j(this);
			var name = $this.data('name');

			$this.children('p').addClass('hide').parent().children('.table__actions').removeClass('hide').children('input').focus();
		});

		$body.on('click', '.js-change-name-state-cancel', function(){
			var $this = j(this);
			var name = $this.parent().parent().data('name');
			$this.prev().prev().val(name);
			$this.parent().addClass('hide').parent().children('p').removeClass('hide').text(name);
		});

		$body.on('click', '.js-change-name-state-update', function(){
			var $this = j(this);
			var $input = $this.prev('input');
			if ($input.val().length === 0) {
				$input.focus();
				return;
			}

			spinner.spin(target);
			var name = $input.val();
			var id = $this.data('id');

			j.post(_root_ + 'extranet/states/action', {
				id: id,
				field: 'post_title',
				value: name
			}, function(data) {
				spinner.stop();
				if (data) {
					$this.parent().addClass('hide').parent().data('name', name).children('p').removeClass('hide').text(name);
				} else {
					jAlert('No se pudo actualizar el nombre del estado. Por favor vuelve a intentarlo.', 'Aviso');
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

	    // Activamos el switch
	  	j("[name='status_active']").bootstrapSwitch({
	  		size: 			'small',
	  		onColor: 		'success',
	  		radioAllOff: 	true
	  	});
	});

	function changeOrder(event, $this, action) {
		event.preventDefault();

		spinner.spin(target);
		var order = $this.data('order');
		var id = $this.data('id');

		j.post(_root_ + 'extranet/states/change', {
			id: id,
			order: order,
			action: action
		}, function(data) {
			spinner.stop();
			if (data) {
				j('.main__grid__content').load(_root_ + 'extranet/states/displayAjax');
			}
		});
	}
})(jQuery);