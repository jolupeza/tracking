var j = jQuery.noConflict();

(function($){
  var $body = j('body');

	j(document).on("ready", function(){
    j('.main__grid__content').load(_root_ + 'extranet/main/displayAjax');

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

    j(document).on('click', '#js-order-search', function(ev){
      ev.preventDefault();
      var href = j(this).data('href');
      var search = j('#js-order-search-text').val();

      if (search.length == 0) {
        j('#js-order-search-text').focus();
        return;
      }

      j('.main__grid__content').load(href + encodeURI(search));
    });

    j(document).on('keypress', '#js-order-search-text', function(event){
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

    j('body').on('change', '#js-order-rows', function(ev) {
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

    j('.login__form').bootstrapValidator({
      // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
      feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
      },
      live: 'enabled',
      fields: {
        login_email: {
          validators: {
            notEmpty: {
              message: 'Campo requerido'
            },
            emailAddress: {
              message: 'Email no es válido'
            }
          }
        },
        login_pass: {
          validators: {
            notEmpty: {
                message: 'Campo requerido'
            },
            stringLength: {
              min: 6,
              max: 30,
              message: 'Este campo debe contener entre 6 y 30 caracteres'
            },
          }
        },
      },
    });

    $body.on('click', '.js-change-status', function(ev){
      ev.preventDefault();
      spinner.spin(target);

      var status = j(this).data('status');
      status = (status == 'initiated') ? 'finalized' : 'initiated';
      var id = j(this).data('id');

      j.post(_root_ + 'extranet/main/action', {
          id: id,
          field: 'post_status',
          value: status
      }, function(data) {
          spinner.stop();
          if (data) {
            j('.main__grid__content').load(_root_ + 'extranet/main/displayAjax');
          }
      });
    });

    $body.on('click', '.js-del-order', function(ev){
      ev.preventDefault();
      spinner.spin(target);
      var $this = j(this);

      jConfirm('¿Desea eliminar el registro?', 'Confirmar eliminación', function(r){
        if (r) {
          var id = $this.data('id');

          j.post(_root_ + 'extranet/main/delete', {
            id: id
          }, function(data) {
            if (data) {
              jAlert('Se eliminó correctamente el registro', 'Aviso');
              j('.main__grid__content').load(_root_ + 'extranet/main/displayAjax');
            } else {
              jAlert('No se pudo eliminar el registro. Por favor vuelva a intentarlo', 'Aviso');
            }
          });
        }

        spinner.stop();
      });
    });
	});
})(jQuery);