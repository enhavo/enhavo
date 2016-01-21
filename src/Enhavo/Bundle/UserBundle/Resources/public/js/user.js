/**
 * Created by jhelbing on 05.01.16.
 */
function User(admin)
{
    var self = this;

    this.initPasswordChange = function(form)
    {
        $(form).find('[data-type=savePassword]').click(function (e) {
            e.preventDefault();
            form = $(form);
            var data = form.serialize();
            var action = form.attr('action');
            $.ajax({
                type: 'POST',
                data: data,
                url: action,
                success: function (response) {
                    console.log(response);
                    admin.overlayClose();
                },
                error: function (jqXHR) {

                }
            });
        });
    };

    var init = function() {
        $(document).on('formOpenAfter', function(event, form) {
            //self.initLinkTypeSelector(form);
            self.initPasswordChange(form);
        });
    };

    init();
}

