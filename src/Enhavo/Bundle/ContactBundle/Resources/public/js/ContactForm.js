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
            console.log(data);

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    $('#contact_message').remove();
                    if(response == 1 || response == 0) {
                        form.append('<div id="contact_message">Nachricht gesendet!</div>');
                        if(response == 1) {
                            self.sendSenderMail(data);
                        }
                    }else {
                        form.append('<div id="contact_message">'+response+'</div>');
                    }
                },
                error: function(response) {
                    $('#contact_message').remove();
                    form.append('<div id="contact_message">Fehler!</div>');
                }
            });
            return false;
        });
    };

    this.sendSenderMail = function(data) {
        var url = "/sendSenderMail";
        var data = data;

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                console.log(1);
            },
            error: function(response) {
               console.log(0);
            }
        });
        return false;
    }
}