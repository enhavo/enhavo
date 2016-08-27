function Cart()
{
  var self = this;

  this.init = function() {
    self.initCartAddForm();
    self.initCartAddSubmit();
  };

  this.initCartAddForm = function() {
    $('[data-form-cart-add]').on('submit', function(event) {
      event.preventDefault();
      var data = $(this).serialize();
      var url = $(this).attr('action');
      var form = $(this);
      $.ajax({
        url: url,
        type: 'post',
        data: data,
        success: function() {
          form.find('[name=quantity]').val('1');
        }
      })
    })
  };

  this.initCartAddSubmit = function() {
    $('[data-submit-cart-add]').on('click', function(event) {
      event.preventDefault();
      $(this).parents('[data-form-cart-add]').submit();
    })
  };
}

var cart = new Cart();
$(function() {
  cart.init();
});