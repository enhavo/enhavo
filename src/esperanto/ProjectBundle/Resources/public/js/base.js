

$(function() {
    newsletter.init()
});

var newsletter = new Newsletter();

function Newsletter() {

    var self = this;

    this.init = function () {
        $("#addEmail").click(function () {
            var url = $("#addEmailForm").attr('action');
            var email = $('input[name="esperanto_newsletter_subscriber[email]"]').val();
            data = $("#addEmailForm").serialize();
            $.post(url, data, function (response) {
                $("#addEmailForm").get(0).reset();

                var code = $.now()+email;
            })
                .fail(function(jqXHR) {
                        var data = JSON.parse(jqXHR.responseText);

                        if(data.code == 400) {
                            var getErrors = function(data, errors) {
                                if(typeof errors == 'undefined') {
                                    errors = [];
                                }

                                for (var key in data) {
                                    if(data.hasOwnProperty(key)) {

                                        if(key == 'errors') {
                                            for (var error in data[key]) {
                                                if (data[key].hasOwnProperty(error) && typeof data[key][error] == 'string') {
                                                    errors.push(data[key][error]);
                                                }
                                            }
                                        }

                                        if (typeof data[key] == 'object') {
                                            getErrors(data[key], errors);
                                        }

                                    }
                                }

                                return errors
                            };

                            var errors = getErrors(data.errors);
                            alert(errors[0]);
                        } else {
                            alert("Error")
                        }
                })
            return false;
        });
    };
}