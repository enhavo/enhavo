/**
 * Created by jhelbing on 10.11.15.
 */
$(function() {
    contact.init()
});

var contact = new ContactForm();

function ContactForm() {

    var self = this;

    this.init = function () {
        $('#contact_form').on('submit', function (event) {
            event.preventDefault();
            var form = $(this);
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    $('#contact_message').remove();
                    form.append('<div id="contact_message">'+response.message+'</div>');
                },
                error: function(response) {
                    $('#contact_message').remove();
                    form.append('<div id="contact_message">Fehler!</div>');
                }
            });
            return false;
        });
    };
}