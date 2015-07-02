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
      self.setFileOrder(form);
    };

    selector.on('click', '.button-add-category' , function(event) {
      event.preventDefault();
      var container = selector.find('.category-container');
      addCategoryForm(container);
    });

    selector.on('click', '.button-delete-category' , function(event) {
      event.preventDefault();
      $(this).parent().parent().remove();
      self.setFileOrder(form);
    });

    selector.find('[data-category-item-list]').sortable({
      update: function() {
        self.setFileOrder(form);
      },
      items: '[data-category-item]'
    });
  };

  this.setFileOrder = function(form) {
    $(form).find('[data-category-item-order]').each(function(index) {
      $(this).val(index);
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



