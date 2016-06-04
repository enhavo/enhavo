var Form = function(router, templating, admin, translator)
{
  var self = this;

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

  this.initInput = function(form) {
    $('input').keypress(function(e) {
      if(e.which == 13) {
        event.preventDefault();
      }
    });
  };

  this.initList = function(form) {

    var initDeleteButton = function(item) {
      $(item).first().find('.button-delete').click(function (e) {
        e.preventDefault();
        $(this).closest('.listElement').remove();
      });
    };

    var initAddButton = function(list, count) {
      list.next().find('.add-another').click(function(e) {
        e.preventDefault();

        // grab the prototype template
        var item = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        item = item.replace(/__name__/g, count);
        count++;

        item = $.parseHTML(item.trim());
        list.append(item);
        initItem(item);
        $(document).trigger('formListAddItem', item);
        setOrderForContainer(list);
      })
    };

    var initItem = function(item) {
      initButtonUp(item);
      initButtonDown(item);
      initDeleteButton(item);
    };

    var initButtonUp = function(item) {
      $(item).on('click', '.button-up', function() {
        var liElement = $(this).parent();
        while(!liElement.hasClass('listElement')) {
          liElement = liElement.parent();
        }
        var list = liElement.parent();
        var index = list.children().index(liElement);

        if(index > 0) { // is not first element
          if(liElement.find('[data-wysiwyg]').length) {
            var editorId = liElement.find('[data-wysiwyg]').attr('id');
            tinymce.execCommand('mceRemoveEditor', false, editorId);
            $(list.children().get(index - 1)).before(liElement); //move element before last
            tinymce.execCommand('mceAddEditor', false, editorId);
          } else {
            $(list.children().get(index - 1)).before(liElement); //move element before last
          }
        }

        setOrderForContainer(list);
      });
    };

    var initButtonDown = function(item) {
      $(item).on('click', '.button-down', function() {
        var liElement = $(this).parent();
        while(!liElement.hasClass('listElement')) {
          liElement = liElement.parent();
        }
        var list = liElement.parent();
        var index = list.children().index(liElement);
        var size = list.children().size();

        if(index < (size - 1)) { // is not last element
          if(liElement.find('[data-wysiwyg]').length) {
            var editorId = liElement.find('[data-wysiwyg]').attr('id');
            tinymce.execCommand('mceRemoveEditor', false, editorId);
            $(list.children().get(index + 1)).after(liElement); //move element after next
            tinymce.execCommand('mceAddEditor', false, editorId);
          } else {
            $(list.children().get(index + 1)).after(liElement); //move element after next
          }
        }

        setOrderForContainer(list)
      });
    };

    var setOrderForContainer = function(list) {
      var orderby = list.attr('data-order');
      list.find("."+orderby).each(function(index) {
        $(this).val(index+1);
      });
    };

    (function(form) {
      $(form).find('.enhavo_list').each(function() {
        var list = $(this);

        $.each(list.children(), function(index, item) {
          initItem($(item));
        });

        setOrderForContainer(list);

        var count = $(this).children().length;
        initAddButton(list, count);
      });
    })(form);
  };

  var init = function() {
    $(document).on('formOpenAfter', function(event, form) {
      self.initDataPicker(form);
      self.initRadioAndCheckbox(form);
      self.initWysiwyg(form);
      self.initSelect(form);
      self.initSorting(form);
      self.initInput(form);
      self.initList(form);
    });

    $(document).on('formListAddItem', function(event, item) {
      self.initSelect(item);
      self.initRadioAndCheckbox(item);
      self.initDataPicker(item);
      self.initInput(item);
      self.initWysiwyg(item);
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
