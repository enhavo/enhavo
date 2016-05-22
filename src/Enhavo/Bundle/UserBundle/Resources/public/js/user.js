/**
 * Created by jhelbing on 05.01.16.
 */
function User(Form)
{
    var self = this;

    this.initPasswordChange = function(form)
    {
        Form.initSave(form);
    };

    var init = function() {
        $(document).on('formOpenAfter', function(event, form) {
            self.initPasswordChange(form);
        });
    };

    init();
}

