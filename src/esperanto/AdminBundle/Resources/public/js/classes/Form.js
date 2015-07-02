var Form = function(router, templating, admin, translator)
{
  var self = this;

  var MessageType = {
    Info: 'info',
    Error: 'error',
    Success: 'success'
  };

  this.initDataPicker = function(form)
  {
    $(form).find('input.datetimepicker').datetimepicker({
      timeFormat: 'hh:mm',
      timeText: 'Zeit',
      hourText: 'Std.',
      minuteText: 'Min.',
      currentText: 'Jetzt',
      closeText: 'Fertig',
      dateFormat: 'dd.mm.yy',
      stepMinute: 5,
      firstDay: 1
    });

    $(form).find('input.datepicker').datepicker({
      closeText: 'Fertig',
      dateFormat: 'dd.mm.yy',
      firstDay: 1
    });
  };

  this.initRadioAndCheckbox = function (form) {
    $(form).find('input[type=radio],input[type=checkbox]').iCheck({
      checkboxClass: 'icheckbox-esperanto',
      radioClass: 'icheckbox-esperanto'
    });
  };

  this.initSelect = function (form) {
    $(form).find('select').select2();
  };

  this.destroyWysiwyg = function(content) {
    content.find('.wysiwyg').each(function() {
      tinymce.remove('#'.$(this).attr('id'));
    });
  };

  this.initWysiwyg = function(form)
  {
    var addTinymce = function(element) {

      var options = {
          // Location of TinyMCE script
          script_url : '/js/lib/tinymce/tinymce.min.js',
          //content_css: "/css/editor.css",
          menubar: false,
          // General options
          plugins: ["advlist autolink lists link image charmap print preview anchor",
              "searchreplace visualblocks code fullscreen",
              "insertdatetime media table contextmenu paste"],
          force_br_newlines : false,
          force_p_newlines : true,
          forced_root_block : "p",
          cleanup : false,
          cleanup_on_startup : false,
          font_size_style_values : "xx-small,x-small,small,medium,large,x-large,xx-large",
          convert_fonts_to_spans : true,
          resize: false,
          relative_urls : false,
          oninit: function(ed) {
              $(ed.contentAreaContainer).droppable({
                  accept: ".imgList li.imgContainer",
                  drop: function(event, ui) {
                      var draggedImg = ui.draggable.find("img");
                      var src = '';
                      if(typeof draggedImg.attr("largesrc") == "undefined") {
                          src = draggedImg.attr("src")
                      } else {
                          src = draggedImg.attr("largesrc");
                      }
                      ed.execCommand('mceInsertContent', false, "<img src=\""+src+"\" />");
                  }
              });
          }
      };

      var config = $(element).data('config');

      if(config.height) {
          options.height = config.height;
      }

      if(config.style_formats) {
         options.style_formats = config.style_formats;
      }

      if(config.formats) {
          options.formats = config.formats;
      }

      if(config.toolbar1) {
          options.toolbar1 = config.toolbar1
      }

      if(config.toolbar2) {
          options.toolbar2 = config.toolbar2
      }

      if(config.content_css) {
        options.content_css = config.content_css
      }

      $(element).tinymce(options);
    };

    $(form).find('[data-wysiwyg]').each(function(index, element) {
      addTinymce(element);
    });
  };

  this.initSave = function (form) {
    $(form).find('[data-button][data-type=save]').click(function() {
      $(this).trigger('formSaveBefore', form);

      form = $(form);
      var data = form.serialize();
      var action = form.attr('action');
      $.ajax({
        type: 'POST',
        data: data,
        url: action,
        success: function(data) {
          $(form).trigger('formSaveAfter', form);
        },
        error: function(jqXHR) {
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
            admin.overlayMessage(message, MessageType.Error);
          } else {
            admin.overlayMessage(translator.trans('error.occurred'), MessageType.Error);
          }
        }
      });
    });
  };

  this.initPreviewButton = function(form) {
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

  this.initDelete = function(form)
  {
    $(form).find('[data-button][data-type=delete]').click(function() {
      var url = $(form).data('delete');
      if(confirm(translator.trans('form.delete.question'))) {
        $.ajax({
          type: 'POST',
          data: {
            _method: 'DELETE'
          },
          url: url,
          success: function() {
            $(form).trigger('formSaveAfter', form);
          },
          error: function() {
            alert(translator.trans('error.occurred'));
          }
        });
      }
    });
  };

  this.initSorting = function(form) {
    var setOrder = function() {
      $(form).find('[data-sort-order]').each(function(index, element) {
        $(element).val(index);
      });
    };

    $(form).find('[data-sort-up-button]').click(function() {
      var item = $(this).parents('[data-sort-row]');
      var container = item.parents('[data-sort-container]');
      var index = container.children().index(item);
      if(index > 0) { // is not first element
        $(container.children().get(index - 1)).before(item); //move element before last
      }
      setOrder();
    });

    $(form).find('[data-sort-down-button]').click(function() {
      var item = $(this).parents('[data-sort-row]');
      var container = item.parents('[data-sort-container]');
      var index = container.children().index(item);
      var size = container.children().size();

      if(index < (size - 1)) { // is not last element
        $(container.children().get(index + 1)).after(item); //move element after next
      }
      setOrder();
    });

    $(form).find('[data-sort-container]').sortable({
      update: function() {
        setOrder();
      },
      axis: 'y',
      cursor: "move",
      handle: '[data-sort-handle]'
    });

    setOrder();
  };


  var init = function() {
    $(document).on('formOpenAfter', function(event, form) {
      self.initDataPicker(form);
      self.initRadioAndCheckbox(form);
      self.initPreviewButton(form);
      self.initWysiwyg(form);
      self.initSave(form);
      self.initDelete(form);
      self.initSelect(form);
      self.initSorting(form);
    });

    $(document).on('formSaveAfter', function() {
      admin.overlayClose();
    });

    $(document).on('formCloseAfter', function(event, content) {
      self.destroyWysiwyg(content);
    });
  };

  init();
};
