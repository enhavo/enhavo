define(['jquery', 'app/Admin', 'app/Form'], function($, admin, form) {
  function Translation() {
    var self = this;

    this.initTranslation = function(form) {
      $(form).find('[data-translation-switch]').click(function() {
        console.log('trans');
      });
    };

    this.init = function () {
      $(document).on('formOpenAfter', function (event, form) {
        self.initTranslation(form);
      });
    };

    self.init();
  }
  return new Translation();
});