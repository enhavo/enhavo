function SubscribeForm($) {

  var self = this;

  this.init = function() {
    self.initSubscribeForm();
  };

  this.initSubscribeForm = function() {
    $('[data-newsletter-subscribe-form]').submit(function(event) {
      event.preventDefault();
      $(document).trigger('newsletter_subscribe_submit');
      var form = $(this);
      $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        success: function(data) {
          $(document).trigger('newsletter_subscribe_complete', {
            success: true,
            message: data.message,
            subscriber: data.subscriber
          });
        },
        error: function(data) {
          $(document).trigger('newsletter_subscribe_complete', {
            success: true,
            message: data.responseJSON.message
          });
        }
      });
    });
  };

  this.init();
}

$(function() {
  new SubscribeForm($);
});