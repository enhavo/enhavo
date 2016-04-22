/**
 * Created by jhelbing on 24.02.16.
 */
$(function() {
    search.init()
});

var search = new SearchForm();

function SearchForm(router, admin) {

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
                    $('#search-results').remove();
                    $(response).insertAfter(form);
                    self.initResults();
                },
                error: function(response) {
                    console.log("Search error.")
                }
            });
            return false;
        });
    };

    this.initResults = function() {
        $('#search-results').on('click', '[data-id]', function() {
            var id = $(this).data('id');
            var route = $(this).data('update-route');
            var url = router.generate(route, {id: id});
            admin.ajaxOverlay(url);
        });
    }
}