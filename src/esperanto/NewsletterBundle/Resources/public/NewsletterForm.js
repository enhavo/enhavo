$(function() {
    newsletter.init()
});

var newsletter = new Newsletter();

function Newsletter() {

    var self = this;

    this.init = function () {
        $("#addEmail").click(function () {
            console.log($(this));
            var url = $(this).data('ajax-route');
            data = $("#addEmailForm").serialize();
            console.log(data);

            $.post(url, data, function (response) {
                alert('Test!');
                $("#addEmailForm").get(0).reset();
            });
            return false;
        });
    };
}