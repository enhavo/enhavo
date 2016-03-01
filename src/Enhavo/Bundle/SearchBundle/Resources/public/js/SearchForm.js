/**
 * Created by jhelbing on 24.02.16.
 */
$(function() {
    search.init()
});

var search = new SearchForm();

function SearchForm() {

    var self = this;

    this.init = function () {
        $('#search_form').on('submit', function (event) {
            event.preventDefault();
            var form = $(this);
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                   console.log("Test1");
                    console.log(response);
                },
                error: function(response) {
                    console.log("Test2");
                    console.log(response);
                }
            });
            return false;
        });
    };
}