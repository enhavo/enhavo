function SubscribeForm($) {

  var self = this;

  this.init = function() {
    self.initSubscribeForm();
  };

  this.initSubscribeForm = function() {
    $('[data-newsletter-subscribe-form]').submit(function(event) {
      event.preventDefault();
      var form = $(this);
      $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        success: function() {
          console.log('success');
        },
        error: function() {
          console.log('error');
        }
      });
    });
  };

  this.init();
}

$(function() {
  new SubscribeForm($);
});