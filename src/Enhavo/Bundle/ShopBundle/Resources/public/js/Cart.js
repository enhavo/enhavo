function Cart()
{
  var self = this;

  this.init = function() {
    self.initCartAddForm();
    self.initCartAddSubmit();
    self.initCartQuantityForm();
    self.initCartItemRemoveLink();
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
}

var cart = new Cart();
$(function() {
  cart.init();
});