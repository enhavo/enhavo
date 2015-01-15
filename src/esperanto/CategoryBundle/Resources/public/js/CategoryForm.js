var CategoryForm = function()
{
  var self = this;

  this.initAddCategories = function(form)
  {
    var selector = $(form);
    var addCategoryForm = function (container) {
      var prototype = container.data('prototype');
      var index = container.children().size();
      var newForm = prototype.replace(/__name__/g, index);
      container.append(newForm);
    };

    selector.on('click', '.button-add-category' , function(event) {
      event.preventDefault();
      var container = selector.find('.category-container');
      addCategoryForm(container);
    });

    selector.on('click', '.button-delete-category' , function(event) {
      event.preventDefault();
      $(this).parent().parent().remove();
    });
  };

  var init = function() {
    $(document).on('formOpenAfter', function(event, form) {
      self.initAddCategories(form);
    });
  };

  init();
};

$(function() {
  new CategoryForm();
});



