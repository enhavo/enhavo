function Shop()
{
  var self = this;

  this.init = function() {
    self.initCartAddForm();
    self.initCartAddSubmit();
    self.initCartQuantityForm();
    self.initCartItemRemoveLink();
    self.initCheckoutForm();
    self.initDifferentBillingAddressCheckbox();
    self.initCouponForm();
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

  this.initCartQuantityForm = function() {
    $('[data-form-cart-quantity]').on('submit', function(event) {
      event.preventDefault();
      var data = $(this).serialize();
      var url = $(this).attr('action');
      var form = $(this);
      $.ajax({
        url: url,
        type: 'post',
        data: data,
        success: function(data) {
          self.updateValues(data, form);
        }
      })
    })
  };

  this.initCartItemRemoveLink = function() {
    $('[data-cart-item-remove]').on('click', function(event) {
      event.preventDefault();
      var url = $(this).attr('href');
      var form = $(this).parents('form');
      $.ajax({
        url: url,
        success: function(data) {
          self.updateValues(data);
          form.remove();
        }
      })
    })
  };

  this.updateValues = function(values, $form) {
    if(values.orderItem && $form) {
      $form.find('[data-cart-item-unit-price]').html(values.orderItem.unitPrice);
      $form.find('[data-cart-item-unit-tax]').html(values.orderItem.unitTax);
      $form.find('[data-cart-item-unit-total]').html(values.orderItem.unitTotal);

      $form.find('[data-cart-item-tax-total]').html(values.orderItem.taxTotal);
      $form.find('[data-cart-item-net-total]').html(values.orderItem.netTotal);
      $form.find('[data-cart-item-total]').html(values.orderItem.total);
    }

    if(values.order) {
      $('[data-cart-total]').html(values.order.total);
      $('[data-cart-net-total]').html(values.order.netTotal);
      $('[data-cart-tax-total]').html(values.order.taxTotal);
      $('[data-cart-discount-total]').html(values.order.discountTotal);
      $('[data-cart-shipping-total]').html(values.order.shippingTotal);
      $('[data-cart-unit-total]').html(values.order.unitTotal);
    }
  };

  this.initCartAddSubmit = function() {
    $('[data-submit-cart-add]').on('click', function(event) {
      event.preventDefault();
      $(this).parents('[data-form-cart-add]').submit();
    })
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

  this.initCouponForm = function()
  {
    var form = $('[data-coupon-form]');
    form.on('submit', function(event) {
      event.preventDefault();
      $.ajax({
        type: 'post',
        url: form.attr('action'),
        data: form.serialize(),
        success: function(data) {
          console.log(data);
        },
        error: function(data) {
          console.log(data);
        }
      })
    });
  };
}

var shop = new Shop();
$(function() {
  shop.init();
});