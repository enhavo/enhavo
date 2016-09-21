function Newsletter(router, translator, admin) {

  var self = this;

  this.initSend = function(router) {
    $(document).on('formOpenAfter', function(event, form) {
      $(form).find('[data-button][data-type=send]').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        var form = $(this).parents('form');
        var data = form.serialize();
        var sent = form.find('[data-newsletter-sent]');
        var route = $(this).attr('data-route');
        var id = form.attr('data-id');
        var url = router.generate(route, { id: id });
        if(sent.length == 0) {
          self.sendNewsletter(data, url, form);
        } else {
          var text = sent.attr('data-sent-text');
          var sendAgain = confirm(text);
          if(sendAgain == true) {
            self.sendNewsletter(data, url, form);
          }
        }
      });
    });
  };

  this.sendNewsletter = function(data, url, form) {
    admin.openLoadingOverlay();
    $.ajax({
      type: 'POST',
      data: data,
      url: url,
      success: function() {
        admin.closeLoadingOverlay();
        admin.overlayMessage(translator.trans('newsletter.action.sent.success'), 'info');
        admin.overlayClose();
      },
      error: function() {
        admin.closeLoadingOverlay();
        admin.overlayMessage(translator.trans('newsletter.action.sent.error'), 'error');
        admin.overlayClose();
      }
    });
  };

  this.initAddEmail = function() {
    $("#addEmail").click(function () {
      var url = $("#addEmailForm").attr('action');
      var email = $('input[name="enhavo_newsletter_subscriber[email]"]').val();
      data = $("#addEmailForm").serialize();
      admin.openLoadingOverlay();
      $.post(url, data, function (response) {
          admin.closeLoadingOverlay();
          $("#addEmailForm").get(0).reset();

          var code = $.now()+email;
        })
        .fail(function(jqXHR) {
          admin.closeLoadingOverlay();
          var data = JSON.parse(jqXHR.responseText);

          if(data.code == 400) {
            var getErrors = function(data, errors) {
              if(typeof errors == 'undefined') {
                errors = [];
              }

              for (var key in data) {
                if(data.hasOwnProperty(key)) {

                  if(key == 'errors') {
                    for (var error in data[key]) {
                      if (data[key].hasOwnProperty(error) && typeof data[key][error] == 'string') {
                        errors.push(data[key][error]);
                      }
                    }
                  }

                  if (typeof data[key] == 'object') {
                    getErrors(data[key], errors);
                  }

                }
              }

              return errors
            };

            var errors = getErrors(data.errors);
            alert(errors[0]);
          } else {
            alert("Error")
          }
        });
      return false;
    });
  };


  var init = function() {
    self.initAddEmail();
    self.initSend(router);
  };

  init();
}