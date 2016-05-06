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
            admin.openLoadingOverlay();
            $.ajax({
                type: 'POST',
                data: data,
                url: action,
                success: function (response) {
                    admin.closeLoadingOverlay();
                    console.log(response);
                    admin.overlayClose();
                },
                error: function (jqXHR) {
                    admin.closeLoadingOverlay();
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

