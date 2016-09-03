function Checkout()
{
  var self = this;

  this.init = function() {
    self.initCheckoutForm();
    self.initDifferentBillingAddressCheckbox();
  };

  this.initCheckoutForm = function()
  {
    var form = $('[data-checkout-form]');
    form.on('submit', function(event) {
      event.preventDefault();
      $.ajax({
        type: 'post',
        url: form.attr('action'),
        data: form.serialize(),
        success: function(data) {
          window.location.href = data.redirect_url;
        },
        error: function(data) {
          console.log(data.responseJSON[0]);
        }
      })
    });
  };

  this.initDifferentBillingAddressCheckbox = function()
  {
    var form = $('[data-checkout-form]');
    form.find('[data-checkout-addressing-different-billing-address]').click(function() {
      var differentBillingAddress = $(this).is(':checked');
      if(!differentBillingAddress) {
        form.find('[data-checkout-billing-address]').hide();
      } else {
        form.find('[data-checkout-billing-address]').show();
      }
    });
  };
}

var checkout = new Checkout();
$(function() {
  checkout.init();
});