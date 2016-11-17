define(['jquery', 'app/Templating', 'app/Admin', 'app/Translator', 'jquery-ui-timepicker', 'jquery-tinymce', 'tinymce'], function($, templating, admin, translator, timepicker, jQueryTinymce, tinymce) {

  var Form = function () {
    var self = this;
    var rootForm = null;
    var placeholderIndex = 0;
    var placeholderToIndexMap = {};

    this.initDataPicker = function (form) {
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

    this.destroyWysiwyg = function (content) {
      content.find('[data-wysiwyg]').each(function () {
        tinymce.remove('#'+$(this).attr('id'));
      });
    };

    this.initWysiwyg = function (form) {
      var addTinymce = function (element) {

        var options = {
          menubar: false,
          // General options
          plugins: ["advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste autoresize enhavo_translation"],
          force_br_newlines: false,
          force_p_newlines: true,
          forced_root_block: "p",
          cleanup: false,
          cleanup_on_startup: false,
          font_size_style_values: "xx-small,x-small,small,medium,large,x-large,xx-large",
          convert_fonts_to_spans: true,
          resize: false,
          relative_urls: false,
          oninit: function (ed) {
            $(ed.contentAreaContainer).droppable({
              accept: ".imgList li.imgContainer",
              drop: function (event, ui) {
                var draggedImg = ui.draggable.find("img");
                var src = '';
                if (typeof draggedImg.attr("largesrc") == "undefined") {
                  src = draggedImg.attr("src")
                } else {
                  src = draggedImg.attr("largesrc");
                }
                ed.execCommand('mceInsertContent', false, "<img src=\"" + src + "\" />");
              }
            });
          }
        };

        var config = $(element).data('config');

        if (config.height) {
          options.height = config.height;
        }

        if (config.style_formats) {
          options.style_formats = config.style_formats;
        }

        if (config.formats) {
          options.formats = config.formats;
        }

        if (config.toolbar1) {
          options.toolbar1 = config.toolbar1
        }

        if (config.toolbar2) {
          options.toolbar2 = config.toolbar2
        }

        if (config.content_css) {
          options.content_css = config.content_css
        }

        $(element).tinymce(options);
      };

      $(form).find('[data-wysiwyg]').each(function (index, element) {
        addTinymce(element);
      });
    };

    this.initSorting = function (form) {
      var setOrder = function () {
        $(form).find('[data-sort-order]').each(function (index, element) {
          $(element).val(index);
        });
      };

      $(form).find('[data-sort-up-button]').click(function () {
        var item = $(this).parents('[data-sort-row]');
        var container = item.parents('[data-sort-container]');
        var index = container.children().index(item);
        if (index > 0) { // is not first element
          $(container.children().get(index - 1)).before(item); //move element before last
        }
        setOrder();
      });

      $(form).find('[data-sort-down-button]').click(function () {
        var item = $(this).parents('[data-sort-row]');
        var container = item.parents('[data-sort-container]');
        var index = container.children().index(item);
        var size = container.children().size();

        if (index < (size - 1)) { // is not last element
          $(container.children().get(index + 1)).after(item); //move element after next
        }
        setOrder();
      });

      $(form).find('[data-sort-container]').sortable({
        update: function () {
          setOrder();
        },
        axis: 'y',
        cursor: "move",
        handle: '[data-sort-handle]'
      });

      setOrder();
    };

    this.initInput = function (form) {
      $('input').keypress(function (e) {
        if (e.which == 13) {
          event.preventDefault();
        }
      });
    };

    this.initList = function (form) {

      var initDeleteButton = function (item) {
        $(item).first().find('.button-delete').click(function (e) {
          e.preventDefault();
          $(this).closest('.listElement').remove();
          self.reindex();
        });
      };

      var initAddButton = function (list) {
        list.next().find('.add-another').click(function (e) {
          e.preventDefault();

          // grab the prototype template
          var item = list.attr('data-prototype');
          var prototype_name = list.attr('data-prototype-name');

          // Generate unique placeholder for reindexing service
          var placeholder = '__name' + placeholderIndex + '__';
          placeholderIndex++;

          // replace prototype_name used in id and name with placeholder
          item = item.replace(new RegExp(prototype_name, 'g'), placeholder);
          item = $.parseHTML(item.trim());

          // Initialize sub-elements for reindexing
          self.initReindexableItem(item, placeholder);

          list.append(item);
          initItem(item);
          $(document).trigger('formListAddItem', item);
          setOrderForContainer(list);
          self.reindex();
        })
      };

      var initItem = function (item) {
        initButtonUp(item);
        initButtonDown(item);
        initDeleteButton(item);
      };

      var initButtonUp = function (item) {
        $(item).on('click', '.button-up', function (event) {
          event.preventDefault();
          event.stopPropagation();

          var liElement = $(this).parent();
          while (!liElement.hasClass('listElement')) {
            liElement = liElement.parent();
          }
          var list = liElement.parent();
          var index = list.children().index(liElement);

          if (index > 0) { // is not first element
            if (liElement.find('[data-wysiwyg]').length) {
              self.destroyWysiwyg(liElement);
              $(list.children().get(index - 1)).before(liElement); //move element before last
              self.initWysiwyg(liElement);
            } else {
              $(list.children().get(index - 1)).before(liElement); //move element before last
            }
          }

          setOrderForContainer(list);
          self.reindex();
        });
      };

      var initButtonDown = function (item) {
        $(item).on('click', '.button-down', function (event) {
          event.preventDefault();
          event.stopPropagation();

          var liElement = $(this).parent();
          while (!liElement.hasClass('listElement')) {
            liElement = liElement.parent();
          }
          var list = liElement.parent();
          var index = list.children().index(liElement);
          var size = list.children().length;

          if (index < (size - 1)) { // is not last element
            if (liElement.find('[data-wysiwyg]').length) {
              self.destroyWysiwyg(liElement);
              $(list.children().get(index + 1)).after(liElement); //move element after next
              self.initWysiwyg(liElement);
            } else {
              $(list.children().get(index + 1)).after(liElement); //move element after next
            }
          }

          setOrderForContainer(list);
          self.reindex();
        });
      };

      var setOrderForContainer = function (list) {
        var orderby = list.attr('data-order');
        list.find("." + orderby).each(function (index) {
          $(this).val(index + 1);
        });
      };

      (function (form) {
        $(form).find('.enhavo_list').each(function () {
          var list = $(this);

          if (typeof list.attr('data-reindexable') != 'undefined') {
            // Save initial index
            list.data('initial-list-index', list.children().length);
          }

          $.each(list.children(), function (index, item) {
            initItem($(item));
          });

          setOrderForContainer(list);
          initAddButton(list);
        });
      })(form);
    };

    this.markInvalidFormElements = function (form, errors) {
      // Get form name prefix
      var namedElements = $(form).find('[name]');
      if (namedElements.length > 0) {
        // Clear previous error state
        $(form).find('[data-form-row]').removeClass('form-error');
        $(form).find('[tabid]').removeClass('form-error-tab');

        // Get name prefix from any named element in the form (e.g. enhavo_article_article)
        var namePrefix = namedElements.first().attr('name');
        namePrefix = namePrefix.substring(0, namePrefix.indexOf('['));

        for (var i = 0; i < errors.length; i++) {
          // Transform from format .a.b.c to format [a][b][c]
          var name = errors[i][0];
          // Replace first . with opening bracket
          name = name.replace('.', '[');
          // Replace all following with closing and opening brackets
          name = name.replace(/\./g, '][');
          // Add prefix and last closing bracket
          name = namePrefix + name + ']';

          var $errorElement = $(form).find('[name="' + name + '"]');
          if ($errorElement.length > 0) {
            // Add error class to row
            $errorElement.parents('[data-form-row]').first().addClass('form-error');
            // Add error class to containing tab
            var tabId = $errorElement.parents('[data-form-tab]').attr('id');
            if (tabId != 'undefined') {
              $(form).find('[tabid="' + tabId + '"]').addClass('form-error-tab');
            }
          }
        }
      }
    };

    this.reindex = function (item, initialize) {
      if (typeof item == 'undefined') {
        item = $(rootForm);
      }

      var currentIndex = 0;
      if (item.data('initial-list-index')) {
        currentIndex = item.data('initial-list-index');
      }

      if (typeof initialize == 'undefined') {
        // First call, initialize names as prototypes and set reindexed flags to 0
        item.find('[data-reindexable]').each(function () {
          $(this).data('reindexed', 0);
        });
        item.find('[data-form-name]').each(function () {
          $(this).attr('name', $(this).attr('data-form-name')).data('reindexed', '0');
        });
        placeholderToIndexMap = {};
      }

      // Recursively call reindex for nested subitems
      item.find('[data-reindexable]').each(function () {
        if ($(this).data('reindexed') == '1') {
          return;
        }
        self.reindex($(this), false);
      });

      // Iterate over all dynamically added elements
      item.find('[data-form-name]').each(function () {
        if ($(this).data('reindexed') == '1') {
          return;
        }
        var placeholder = $(this).attr('data-form-placeholder');
        var index;
        if (placeholderToIndexMap.hasOwnProperty(placeholder)) {
          index = placeholderToIndexMap[placeholder];
        } else {
          index = currentIndex;
          placeholderToIndexMap[placeholder] = currentIndex;
          currentIndex++;
        }
        $(this).attr('name', $(this).attr('name').replace(placeholder, index));

        // Also replace placeholder in all elements containing the same placeholder that have been reindexed already
        $(this).find('[name]').each(function () {
          $(this).attr('name', $(this).attr('name').replace(placeholder, index));
        });

        // Set reindexed flag
        $(this).data('reindexed', '1');
      });

      if (typeof initialize == 'undefined') {
        // initial call, every recursive call returned
        // go over all elements with names and replace remaining placeholders according to placeholder to index map
        item.find('[name]').each(function () {
          for (var placeholder in placeholderToIndexMap) {
            if (placeholderToIndexMap.hasOwnProperty(placeholder)) {
              if ($(this).attr('name').indexOf(placeholder) > -1) {
                $(this).attr('name', $(this).attr('name').replace(placeholder, placeholderToIndexMap[placeholder]));
              }
            }
          }
        });
      }
    };

    this.initReindexableItem = function(item, placeholder) {
      $(item).find('[name]').each(function () {
        $(this).attr('data-form-name', $(this).attr('name')).attr('data-form-placeholder', placeholder);
      });
    };

    var init = function () {
      $(document).on('formOpenAfter', function (event, form) {
        rootForm = form;
        self.initDataPicker(form);
        self.initRadioAndCheckbox(form);
        self.initWysiwyg(form);
        self.initSelect(form);
        self.initSorting(form);
        self.initInput(form);
        self.initList(form);
      });

      $(document).on('formListAddItem', function (event, item) {
        self.initSelect(item);
        self.initRadioAndCheckbox(item);
        self.initDataPicker(item);
        self.initInput(item);
        self.initWysiwyg(item);
        self.initList(item);
      });

      $(document).on('formCloseAfter', function (event, content) {
        self.destroyWysiwyg(content);
      });
    };

    init();
  };

  return new Form();
});
