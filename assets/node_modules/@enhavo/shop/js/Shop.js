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
      var data = $(this).serializeArray();
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
      form.remove();
      $.ajax({
        url: url,
        success: function(data) {
          self.updateValues(data);
        }
      })
    })
  };

  this.updateValues = function(values, $form) {
    var data = null;
    if(values.order) {
      data = values.order;
    }
    if(values.cart) {
      data = values.cart;
    }

    if($form && data && data.items) {
      var itemId = $form.data('item-id');
      var itemData = null;
      $.each(data.items, function(index, item) {
        if(item.id == itemId) {
          itemData = item;
          return false;
        }
      });

      if(itemData) {
        $form.find('[data-cart-item-unit-price]').html(itemData.unit_price);
        $form.find('[data-cart-item-unit-tax]').html(itemData.unit_tax);
        $form.find('[data-cart-item-unit-total]').html(itemData.unit_total);

        $form.find('[data-cart-item-tax-total]').html(itemData.tax_total);
        $form.find('[data-cart-item-unit-price-total]').html(itemData.unit_price_total);
        $form.find('[data-cart-item-total]').html(itemData.total);
      }
    }

    if(data) {
      $('[data-cart-total]').html(data.total);
      $('[data-cart-unit-price-total]').html(data.unit_price_total);
      $('[data-cart-tax-total]').html(data.tax_total);
      $('[data-cart-discount-total]').html(data.discount_total);
      $('[data-cart-shipping-total]').html(data.shipping_total);
      $('[data-cart-unit-total]').html(data.unit_total);
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
      var data = form.serialize();
      $.ajax({
        type: 'post',
        url: form.attr('action'),
        data: data,
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
    $('[data-coupon-redeem]').prop('disabled', false).click(function (e) {
      e.preventDefault();
      var $form = $(this).parents('form');
      var $couponForm = $(this).parents('[data-coupon-form]');
      var data = $form.serialize();

      $.ajax({
        type: 'post',
        url: $couponForm.data('reedem-action'),
        data: data,
        success: function(data) {
          if(data.coupon) {
            location.reload();
          } else {
            base.showOverlayMsg('Gutschein Code existiert nicht', null, true);
          }
        },
        error: function(data) {
          console.log(data);
        }
      });
    });

    $('[data-coupon-cancel]').prop('disabled', false).click(function (e) {
      e.preventDefault();
      var $couponForm = $(this).parents('[data-coupon-form]');

      $.ajax({
        type: 'post',
        url: $couponForm.data('cancel-action'),
        success: function(data) {
          location.reload();
        },
        error: function(data) {
          console.log(data);
        }
      });
    });
  };
}

var shop = new Shop();
$(function() {
  shop.init();
});