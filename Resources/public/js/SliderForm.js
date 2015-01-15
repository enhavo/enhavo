/**
 * Created by gseidel on 10/09/14.
 */

function SliderForm(router)
{
  var self = this;

  this.initLinkTypeSelector = function(form) {

    var currentType = '';
    $(form).find('.link-type-selector input').each(function() {
      if($(this).attr('checked') == 'checked') {
        currentType = $(this).val();
      }
    });
    self.toggleType(form, currentType);

    $(form).find('.link-type-selector input').on('ifChecked', function(event) {
      var type = $(this).val();
      self.toggleType(form, type);
    });
  };

  this.toggleType = function(form, type)
  {
    $(form).find('.link-type-page, .link-type-news, .link-type-reference, .link-type-external').each(function() {
      var regex = /link-type-([A_Za-z]+)/gi;
      var elementClass = $(this).attr('class');
      var elementType = regex.exec(elementClass)[1];

      if(type == elementType) {
        $(this).parents('.row').show();
      } else {
        $(this).parents('.row').hide();
      }
    });
  };

  var init = function() {
    $(document).on('formOpenAfter', function(event, form) {
      self.initLinkTypeSelector(form);
    });
  };

  init();
}

var sliderForm = new SliderForm(Routing);