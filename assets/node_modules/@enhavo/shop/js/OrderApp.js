define(['jquery', 'app/Index', 'app/Router'], function($, index, router) {

  function OrderApp() {
    var self = this;

    this.init = function() {
      self.initForm()
    };

    this.initForm = function() {
      $(document).on('formOpenAfter', function(event, form) {
        self.initDifferentBillingAddressSwitch(form);
        self.initBillingButton(form)
        self.initPackingSlipButton(form);
      });
    };

      this.initBillingButton = function(form) {
          $(form).find('[data-button][data-type="shop_billing"]').click(function(event) {
              event.preventDefault();
              var id = $(form).data('id');
              var data = $(form).serialize();
              var url = router.generate('enhavo_shop_order_billing', {id: id, data: data});
              window.location.href = url;
          });
      };

    this.initPackingSlipButton = function(form) {
        $(form).find('[data-button][data-type="shop_packing_slip"]').click(function(event) {
            event.preventDefault();
            var id = $(form).data('id');
            var url = router.generate('enhavo_shop_order_packing_slip', {id: id});
            window.location.href = url;
        });
    };

    this.initDifferentBillingAddressSwitch = function (form) {
      $(form).find('[data-different-billing-address] input').on('ifChecked', function() {
        var differentBillingAddress = $(form).find('[data-different-billing-address] input:checked').val();
        if(differentBillingAddress == 'true') {
          $(form).find('[data-billing-address]').show();
        } else {
          $(form).find('[data-billing-address]').hide();
        }
      });
    };

    this.init();
  }

  $(function() {
    new OrderApp;
  });
});