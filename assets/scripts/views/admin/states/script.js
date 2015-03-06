var j = jQuery.noConflict();

(function($){
  var $body = j('body');

  j(document).on("ready", function(){
		// Activamos el datetimepicker para agregar un nuevo suscriptor
    j('#datetimepicker-add').datetimepicker({
      pickTime: false
    });

    // Activamos el datetimepicker al momento de editar suscriptor
    j('#datetimepicker-edit').datetimepicker({
      defaultDate:  moment(j("#datetimepicker-edit").data('datetime'))
    });

    // Cargamos los datos con los suscriptores.
    j('.content').load(_root_ + 'admin/states/displayAjax');

    // Al posicionarnos encima de un item nos mostrará las opciones que podemos realizar con él
    j(document).on({
      mouseenter: function() {
        j(this).find('.opt-post').show();
      },
      mouseleave: function() {
        j(this).find('.opt-post').hide();
      }
    }, '.view-option-post');

    j(document).on('click', ".pagination-digg li a", function(e) {
      e.preventDefault();
    	var href = j(this).attr("href");
    	j('.content').load(href);
  	});

  	j(document).on('click', ".link-ajax", function(e) {
    	e.preventDefault();
    	var href = j(this).attr("href");
    	j('.content').load(href);
  	});

  	j(document).on('click', '#goto-btn', function(ev){
  		ev.preventDefault();
  		var $page = parseInt(j('.goto').val());
  		var $no_of_pages = parseInt(j(this).data('maxpage'));
  		if ($page > 0 && $page <= $no_of_pages) {
    		var href = j(this).data('href');
    		var $offset = ($page - 1) * parseInt(j(this).data('limit'));
    		j('.content').load(href + $offset);
  		} else {
    		return false;
  		}
  	});

  	j(document).on('click', '#js-state-search', function(ev){
      ev.preventDefault();
      var href = j(this).data('href');
      var search = j('#js-state-search-text').val();

      j('.content').load(href + encodeURI(search));
    });

  	// Activamos el switch
  	j("[name='state_status']").bootstrapSwitch({
  		size: 			'small',
  		onColor: 		'success',
  		radioAllOff: 	true
  	});

    j('#js-frm-states').bootstrapValidator({
      // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
      },
      live: 'enabled',
      fields: {
        state_name: {
          validators: {
            notEmpty: {
              message: 'Campo requerido'
            }
          }
        },
      },
    });

    $body.on('click', '.js-change-status', function(ev){
      ev.preventDefault();
      spinner.spin(target);

      var status = j(this).data('status');
      var id = j(this).data('id');
      var redirect= j(this).data('return');

      j.post(_root_ + 'admin/states/action', {
        id: id,
        field: 'post_status',
        value: status
      }, function(data) {
        spinner.stop();
        if (data) {
          j('.content').load(_root_ + redirect);
        }
      });
    });

    $body.on('click', '.js-state-del', function(ev) {
      ev.preventDefault();
      var id = j(this).data('id');

      spinner.spin(target);

      jConfirm('¿Desea eliminar este registro?', '¡Eliminación!', function(r) {
        if (r) {
          j.post(_root_ + 'admin/states/delete', {
            id: id
          }, function(data) {
            if (data) {
              j('.content').load(_root_ + 'admin/states/displayAjax');
              jAlert('¡Se eliminó correctamente la suscripción!', 'Aviso');
            } else {
              jAlert('¡No se pudo eliminar el registro. Por favor vuelva a intentarlo!', 'Aviso');
            }
          });
        }
          spinner.stop();
      });
    });

    $body.on('click', '.js-change-order-down', function(event){
      changeOrder(event, j(this), 'down');
    });

    $body.on('click', '.js-change-order-up', function(event){
      changeOrder(event, j(this), 'up');
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

    // Quitar imagen destacada
    j('#js-remove-avatar').on('click', function(ev){
      ev.preventDefault();
      var figure = j('.thumbnails');
      j('#fieldID').val('');
      figure.find('img').attr('src', '').parent().addClass('hidden');
      j('.iframe-btn').removeClass('hidden');
    });
	});

  function changeOrder(event, $this, action) {
    event.preventDefault();

    spinner.spin(target);
    var order = $this.data('order');
    var id = $this.data('id');

    j.post(_root_ + 'admin/states/change', {
      id: id,
      order: order,
      action: action
    }, function(data) {
      spinner.stop();
      if (data) {
        j('.content').load(_root_ + 'admin/states/displayAjax');
      }
    });
  }
})(jQuery);