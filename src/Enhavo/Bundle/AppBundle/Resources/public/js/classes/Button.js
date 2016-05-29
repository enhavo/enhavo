function Button(admin, router, translator)
{
  var self = this;

  this.initDelete = function(form)
  {
    $(form).find('[data-button][data-type=delete]').click(function() {
      var url = $(form).data('delete');
      if(confirm(translator.trans('form.delete.question'))) {
        admin.openLoadingOverlay();
        $.ajax({
          type: 'POST',
          data: {
            _method: 'DELETE'
          },
          url: url,
          success: function() {
            admin.closeLoadingOverlay();
            $(form).trigger('formSaveAfter', form);
          },
          error: function() {
            admin.closeLoadingOverlay();
            alert(translator.trans('error.occurred'));
          }
        });
      }
    });
  };

  this.initSave = function (form) {
    $(form).find('[data-button][data-type=save]').click(function(event) {
      event.preventDefault();
      $(this).trigger('formSaveBefore', form);

      form = $(form);
      var data = form.serialize();
      var action = form.attr('action');
      admin.openLoadingOverlay();
      $.ajax({
        type: 'POST',
        data: data,
        url: action,
        success: function(data) {
          admin.closeLoadingOverlay();
          $(form).trigger('formSaveAfter', form);
        },
        error: function(jqXHR) {
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
            var message = '<b>' + errors.join('<br /><br />') + '</b>';
            admin.overlayMessage(message, admin.MessageType.Error);
          } else {
            admin.overlayMessage(translator.trans('error.occurred'), admin.MessageType.Error);
          }
        }
      });
    });
  };

  this.initPreview = function(form) {
    $(form).find('[data-button][data-type=preview]').click(function(e) {
      e.preventDefault();
      e.stopPropagation();
      var route = $(this).data('route');
      var link = router.generate(route);
      admin.iframeOverlay(form,link,{
        submit: true
      });
    });
  };

  this.initCancel = function(form) {
    $(form).find('[data-button][data-type=cancel]').click(function() {
      admin.overlayClose();
    });
  };

  this.init = function() {
    $(document).on('formOpenAfter', function(event, form) {
      self.initSave(form);
      self.initDelete(form);
      self.initPreview(form);
      self.initCancel(form);
    });
  };

  self.init();
}









