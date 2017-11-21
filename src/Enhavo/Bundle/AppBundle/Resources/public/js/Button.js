define(['jquery', 'app/Admin', 'app/Form', 'app/Router', 'app/Translator'], function($, admin, formScript, router, translator) {

  function Button() {
    var self = this;

    this.initDelete = function (form) {
      $(form).find('[data-button][data-type=delete]').click(function (event) {
        event.preventDefault();
        var url = $(form).data('delete');
        if (confirm(translator.trans('form.delete.question'))) {
          admin.openLoadingOverlay();
          $.ajax({
            type: 'POST',
            data: {
              _method: 'DELETE'
            },
            url: url,
            success: function () {
              admin.closeLoadingOverlay();
              admin.overlayClose();
              $(document).trigger('formSaveAfter', form);
            },
            error: function () {
              admin.closeLoadingOverlay();
              alert(translator.trans('error.occurred'));
            }
          });
        }
      });
    };

    this.initSave = function (form) {
      $(form).find('[data-button][data-type=save]').click(function (event) {
        event.preventDefault();
        $(this).trigger('formSaveBefore', form);
        var close = $(this).data('close');
        var $form = $(form);
        var data = $form.serialize();
        var url = $form.attr('action');
        if ($(this).data('route')) {

          if ($(this).data('route-parameters')) {
            parameters = $(this).data('route-parameters');
          } else {
            var parameters = {};
            if ($(this).data('id')) {
              parameters.id = $(this).data('id');
            }
          }
          url = router.generate($(this).data('route'), parameters);
        }

        admin.openLoadingOverlay();
        $.ajax({
          type: 'POST',
          data: data,
          url: url,
          success: function () {
            admin.closeLoadingOverlay();
            if(!close) {
              admin.overlayClose();
              var url = $(form).attr('action');
              admin.ajaxOverlay(url);
            } else {
              admin.overlayClose();
            }
            $(document).trigger('formSaveAfter', form);
          },
          error: function (jqXHR) {
            admin.closeLoadingOverlay();
            var data = JSON.parse(jqXHR.responseText);

            if (data.code == 400) {
              var getErrors = function (data, errors, position) {
                if (typeof errors == 'undefined') {
                  errors = [];
                }
                if (typeof position == 'undefined') {
                  position = '';
                }

                if (data.hasOwnProperty('errors')) {
                  for (var error in data['errors']) {
                    if (data['errors'].hasOwnProperty(error) && typeof data['errors'][error] == 'string') {
                      errors.push([position, data['errors'][error]]);
                    }
                  }
                }

                if (data.hasOwnProperty('children')) {
                  for (var key in data['children']) {
                    if (data['children'].hasOwnProperty(key)) {
                      if (typeof data['children'][key] == 'object') {
                        getErrors(data['children'][key], errors, position + '.' + key);
                      }

                    }
                  }
                }

                return errors
              };

              var errors = getErrors(data.errors);
              var message = '';
              for (var i = 0; i < errors.length; i++) {
                if (message === '') {
                  message += '<b>';
                } else {
                  message += '<br /><br />';
                }
                if (errors[i][0] == '') {
                  message += errors[i][1];
                } else {
                  message += errors[i][0].substring(errors[i][0].lastIndexOf('.') + 1) + ': ' + errors[i][1];
                }

              }
              message += '</b>';
              admin.overlayMessage(message, admin.MessageType.Error);
              formScript.markInvalidFormElements(form, errors);
            } else {
              admin.overlayMessage(translator.trans('error.occurred'), admin.MessageType.Error);
            }
          }
        });
      });
    };

    this.initPreview = function (form) {
      $(form).find('[data-button][data-type=preview]').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var route = $(this).data('route');
        var link = router.generate(route);

        var data = {
          form: form,
          route: route,
          link: link
        };

        $(document).trigger('previewOpenBefore', data);

        admin.iframeOverlay(data.form, data.link, {
          submit: true
        });
      });
    };

    this.initDuplicate = function (form) {
      $(form).find('[data-button][data-type=duplicate]').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var route = $(this).data('route');

        admin.confirm(translator.trans('message.duplicate.confirm'), function() {
          var link = router.generate(route, {id: $(form).data('id')});
          admin.overlayClose();
          admin.openLoadingOverlay();
          $.ajax({
            url: link,
            success: function () {
              admin.closeLoadingOverlay();
              admin.overlayMessage(translator.trans('message.duplicate.success'), admin.MessageType.Success);
              $(document).trigger('formSaveAfter', form);
            },
            error: function (data) {
              admin.closeLoadingOverlay();
              var message = 'error.occurred';
              if (data.status == 403) {
                message = 'error.forbidden';
              }
              admin.overlayMessage(translator.trans(message), admin.MessageType.Error);
            }
          });
        });
      });
    };

    this.initCancel = function (form) {
      $(form).find('[data-button][data-type=cancel]').click(function (e) {
        e.preventDefault();
        admin.overlayClose();
      });
    };

    this.initDownload = function (form) {
      $(form).find('[data-button][data-type=download]').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var route = $(this).data('route');
        $.ajax({
          type : 'post',
          url: router.generate(route),
          data: $(form).serialize(),
          success: function(data) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:application/octet-stream;base64,' + data.data);
            element.setAttribute('download', data.filename);
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
          }
        });
      });
    };

    this.init = function () {
      $(document).on('formOpenAfter', function (event, form) {
        self.initSave(form);
        self.initDelete(form);
        self.initPreview(form);
        self.initCancel(form);
        self.initDuplicate(form);
        self.initDownload(form);
      });
    };

    self.init();
  };

  return new Button();
});








