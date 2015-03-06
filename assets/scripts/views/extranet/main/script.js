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
        href_new = _root_ + href_arr[4] + '/' + href_arr[5] + '/' + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + 0 + '/' + href_arr[11];
        //href_new = _root_ + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + href_arr[10] + '/' + href_arr[11] + '/' + 0 + '/' + href_arr[13];
      } else {
        href_new = _root_ + href_arr[4] + '/' + href_arr[5] + '/' + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + value + '/' + href_arr[11];
        //href_new = _root_ + href_arr[6] + '/' + href_arr[7] + '/' + href_arr[8] + '/' + href_arr[9] + '/' + href_arr[10] + '/' + href_arr[11] + '/' + value + '/' + href_arr[13];
      }

      spinner.stop();
      j('.main__grid__content').load(href_new);
    });
    // Al presionar en flecha de first page nos mueve a la siguiente página
    /*j('.page-scroll').on('click', function(ev){
      ev.preventDefault();
      var $anchor = j(this);
      j('html, body').stop().animate({
        scrollTop: j($anchor.attr('href')).offset().top,
      }, 2000, 'easeInOutExpo');
    });*/

    // Al cerrar el modal para registro de usuario limpiamos los input
    /*j('#modal-register-user').on('hide.bs.modal', function(ev){
      j('.register_user').bootstrapValidator('resetForm', true);

      j('#user_term').removeAttr('checked');
      if (j('#user_term').next().hasClass('text-danger')) {
        j('#user_term').next().removeClass('text-danger');
      }
    })*/

    // Activamos el datetimepicker para la fecha de cumpleaños
    //j('#datetimepicker-birthday').datetimepicker();

    /*j('.register_user').bootstrapValidator({
      // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
      feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
      },
      live: 'enabled',
      fields: {
        user_name: {
          validators: {
            notEmpty: {
              message: 'Campo requerido'
            },
            regexp: {
              regexp: /^[a-zA-ZñÑ\s\W]/,
              message: 'Sólo puede contener caracteres alfabeticos'
            }
          }
        },
        user_email: {
          validators: {
            notEmpty: {
              message: 'Campo requerido'
            },
            emailAddress: {
              message: 'Email no es válido'
            }
          }
        },
        user_birthday: {
          validators: {
            notEmpty: {
              message: 'Debe indicar fecha'
            },
            date: {
              format: 'YYYY/MM/DD',
              message: 'Fecha no válida',
            }
          }
        },
        user_term: {
          validators: {
            choice: {
              min: 1,
              max: 1,
              message: 'Marcar si está de acuerdo con los términos y condiciones'
            }
          }
        }
      },
    })
    .on('error.field.bv', function(e, data) {
      var field = e.target;
      j('small.help-block[data-bv-result="INVALID"]').addClass('hide');
    })
    .on('success.form.bv', function(e){
      e.preventDefault();
      spinner.spin(target);

      var $this = j(this);
      var $form = j(e.target);
      var dataArray = $form.serializeArray();

      var token = dataArray[0].value;
      var name = dataArray[1].value;
      var email = dataArray[2].value;
      var birthday = dataArray[3].value;

      if (dataArray.length == 4) {
        spinner.stop();
        j('#user_term').next().addClass('text-danger');
        return;
      }

      j.post(_root_ + 'portada/registerUser', {
        token         : token,
        user_name     : name,
        user_email    : email,
        user_birthday : birthday,
      }, function(data) {
        j('#modal-register-user').modal('hide');
        spinner.stop();

        if (data === '1') {
          alert('Se registró correctamente su cuenta. Por favor verifica tu cuenta de email para activar tu cuenta.');
        } else if (data === '2') {
          alert('Se registró pero no pudimos enviar el correo de verificación. Vuelve a registrarte.');
        } else if (data === '3') {
          alert('Hemos actualizado tus datos con tu cuenta de facebook. Por favor logueate con cualquier de los dos modos.');
        } else if (data === '4'){
          alert('Se registró correctamente su cuenta. Por favor verifica tu cuenta de email para activar tu cuenta.');
          window.location = _root_;
        } else {
          alert('No pudimos registrar tu cuenta por favor vuelva a intentarlo.');
        }
      });
    });

    j('#datetimepicker-birthday').on('dp.change dp.show', function(e) {
      j('.register_user').formValidation('revalidateField', 'user_birthday');
    });

    j('#user_birthday').on('focusout', function(){
      j('.register_user').formValidation('revalidateField', 'user_birthday');
    });

    // Al momento de presionar aceptar términos y condiciones verifica si los campos del formulario de registro son válidos y de ser así habilitamos el botón de envio
    j('#user_term').on('click', function(ev){
      var span = j(this).next();

      if (span.hasClass('text-danger')) {
        span.removeClass('text-danger');
      }

      // Si los campos del formulario de registro estan validados activamos el botón de UNIRME
      if (j('.register_user').data('bootstrapValidator').isValid()) {
        j('#btn_register_user').removeAttr('disabled');
      }
    });

    j('#modal-register-password').on('shown.bs.modal', function(ev){
      j('#user_password').focus();
    });
    j('#modal-register-password').modal('show');

    j('.register_password').bootstrapValidator({
      // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
      feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
      },
      live: 'enabled',
      fields: {
        user_password: {
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
        user_repassword: {
          validators: {
            notEmpty: {
                message: 'Campo equerido'
            },
            stringLength: {
              min: 6,
              max: 30,
              message: 'Este campo debe contener entre 6 y 30 caracteres'
            },
            identical: {
              field: 'user_password',
              message: 'Las contraseñas no coinciden'
            }
          }
        },
      },
    }).on('success.form.bv', function(e){
      e.preventDefault();
      spinner.spin(target);

      var $this = j(this);
      var $form = j(e.target);
      var dataArray = $form.serializeArray();

      var token = dataArray[0].value;
      var password = dataArray[1].value;
      var repassword = dataArray[2].value;
      var id = dataArray[3].value;

      j.post(_root_ + 'portada/registerPassUser', {
        token         : token,
        user_id       : id,
        user_password : password,
        user_repassword : repassword
      }, function(data) {
        spinner.stop();
        if (data) {
          j('#modal-register-password').modal('hide');

          j.post(_root_ + 'portada/loginJs', {
            id: id,
            pass: password
          }, function(data) {
            if (data) {
              window.location = _root_ + 'reminders';
            } else {
              window.location = _root_;
            }
          });
        } else {
          j('.register_password').bootstrapValidator('resetForm', true);
          alert('Por favor vuelve a ingresar tu password.');
        }
      });
    });

    j('#link-create-account').on('click', function(ev){
      ev.preventDefault();
      j('#modal-login-user').modal('hide');
      j('#modal-register-user').modal('show');
    });*/

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

    // Al cerrar el modal para login de usuario limpiamos los input
    /*j('#modal-login-user').on('hide.bs.modal', function(ev){
      j('.login_user').bootstrapValidator('resetForm', true);
    });*/

    /*j('#modal-login-user').on('shown.bs.modal', function(ev){
      j('#login_email').focus();
    });

    j('#modal-register-user').on('shown.bs.modal', function(ev){
      j('#user_name').focus();
    });*/
	});
})(jQuery);