define(['jquery', 'app/Index'], function($, index) {

  function OrderApp() {
    var self = this;

    this.init = function() {
      self.initForm()
    };

    this.initForm = function() {
      $(document).on('formOpenAfter', function(event, form) {
        self.initDifferentBillingAddressSwitch(form);
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