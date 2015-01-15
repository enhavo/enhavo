/**
 * Created by gseidel on 30/08/14.
 */

function ContentForm(router)
{
  var self = this;

  this.initContentForm = function(form) {
    $(form).find('.contentForm').each(function() {

      var contentForm = $(this);
      var menu = $(this).find('.menu');
      var container = $(this).find('.item-container');
      var addButton = null;
      var index = container.children().length;

      var addItem = function(itemName) {

        var url = router.generate('esperanto_content_item', {
          name: itemName
        });

        var formName = addButton.attr('data-name') + '[items]['+index+']';
        index++;

        $.ajax({
          type: 'POST',
          data: {
            formName: formName
          },
          url: url,
          success: function(data) {
            var position = addButton.attr('data-position');
            data = $.parseHTML(data);

            if(position == 'top') {
              contentForm.find('.item-container').prepend(data);
            }
            if (position == 'bottom') {
              container.append(data);
            }

            $(document).trigger('contentAddAfter', [data]);

            setOrderForContainer(container);
          }
        });
      };

      var setOrderForContainer = function(container) {
        container.children().children('.orderInput').each(function(index, element) {
          $(element).val(index+1);
        });
      };

      var init = function() {
        setOrderForContainer(container);
      };

      contentForm.on('click', '.addButton', function() {

        if(menu.css('display') == 'none') {
          var position = $(this).position();


          var data = $.parseHTML(data);

          if(position == 'top') {
            contentForm.find('.item-container').prepend(data);
          }
          if (position == 'bottom') {
            container.append(data);
          }

          var top = $(this).attr('data-position') == 'top';
          menu.removeClass('topTriangle');
          menu.removeClass('bottomTriangle');
          if(top) {
            menu.addClass('topTriangle');
            menu.css('top', 30 + position.top + 'px');
          } else {
            menu.addClass('bottomTriangle');
            menu.css('top', -80 + position.top + 'px');
          }
          menu.css('left', position.left + 'px');

          menu.fadeIn(200);


          addButton = $(this);
        } else {
          menu.hide();
        }


      });

      contentForm.on('click', '.addItem', function() {
        var itemName = $(this).attr('data-item');
        menu.hide();
        addItem(itemName);
      });

      contentForm.on('click', '.button-up', function() {
        var item = $(this).parent().parent();
        var container = item.parent();
        var index = container.children().index(item);

        if(index > 0) { // is not first element
          $(container.children().get(index - 1)).before(item); //move element before last
        }

        setOrderForContainer(container);
      });

      contentForm.on('click', '.button-down', function() {
        var item = $(this).parent().parent();
        var container = item.parent();
        var index = container.children().index(item);
        var size = container.children().size();

        if(index < (size - 1)) { // is not last element
          $(container.children().get(index + 1)).after(item); //move element after next
        }

        setOrderForContainer(container)
      });

      contentForm.on('click', '.button-delete', function() {
        var item = $(this).parent().parent();
        var container = item.parent();
        item.remove();
        setOrderForContainer(container);
      });

      init();
    });
  };

  var init = function() {
    $(document).on('formOpenAfter', function(event, form) {
      self.initContentForm(form);
    });
  };

  init();
}

var contentForm = new ContentForm(Routing);

$(function() {
  $(document).on('contentAddAfter', function(event, data) {
    uploadForm.initUploadForm(data);
    form.initWysiwyg(data);
  });
});
