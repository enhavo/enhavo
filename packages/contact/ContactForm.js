function ContactForm()
{
    var self = this;

    this.init = function ()
    {
        $(function() {
            self.initContactForms();
        });
    };

    this.initContactForms = function() {
        $('[data-contact-form]').on('submit', function (event) {
            event.preventDefault();
            var form = $(this);
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    form.find('[data-response-container]').html(
                      '<div class="contact-message-success">'+response.message+'</div>'
                    );
                },
                error: function(response) {
                    form.find('[data-response-container]').html(
                      '<div id="contact-message-error">'+response.responseJSON.message+'</div>'
                    );
                }
            });
            return false;
        });
    };

    this.init();
}

var contact = new ContactForm();