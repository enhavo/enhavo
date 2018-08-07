define(['jquery', 'app/Router'], function($, router) {

  function NewsletterApp() {
    var self = this;

    this.initViewButton = function (form) {
      console.log('initViewButton');
      $(form).find('[data-button][data-type=newsletter_view]').click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        var route = $(this).data('route');
        var parameters = {
          slug: $(this).data('slug')
        };
        var link = router.generate(route, parameters);

        console.log('Link: ' + link);

        window.open(link,'_blank');
      });
    };

    this.init = function () {
      $(document).on('formOpenAfter', function (event, form) {
        self.initViewButton(form);
      });
    };

    self.init();
  };

  return new NewsletterApp();
});
