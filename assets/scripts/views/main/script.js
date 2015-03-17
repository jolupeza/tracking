var j = jQuery.noConflict();

(function($){
  var $body = j('body');

	j(document).on("ready", function(){
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

    j('.panel__grid__content').load(_root_ + 'main/displayAjax');

    $body.on('click', ".pagination-digg li a", function(e) {
      e.preventDefault();
      spinner.spin(target);
      var href = j(this).attr("href");
      j('.panel__grid__content').load(href);
      spinner.stop();
    });

    $body.on('click', ".link-ajax", function(e) {
      e.preventDefault();
      spinner.spin(target);
      var href = j(this).attr("href");
      j('.panel__grid__content').load(href);
      spinner.stop();
    });

    j('#js-change-month').on('change',function(){
      spinner.spin(target);
      var $this = j(this);
      var month = $this.val();

      j('.panel__grid__content').load(_root_ + 'main/displayAjax/3/id/desc/10/' + month);
      spinner.stop();
    });

    j('.js-view-obs-state').on('click', function(){
      var $this = j(this);
      var id = $this.data('id');
      var title = $this.data('title');
      var obs = $this.data('obs');
      var textarea = j('#js-text-obs');

      textarea.text('').text(obs);
      j('#js-title-obs').text('').text('Observaciones de ' + title);
      j('.main__states__item').removeClass('active');
      $this.parent().addClass('active');
    });
	});
})(jQuery);