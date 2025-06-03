define(['jquery', 'app/Admin', 'app/Router', 'app/Form'], function($, admin, router, form) {
    function SearchForm()
    {
        var self = this;

        this.init = function () {

            form.initRadioAndCheckbox('#search_form');

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

            $("[data-show-search-options]").on("click", function(event) {
                event.preventDefault();
                $("[data-search-options]").fadeToggle(300);
            })
        };

        this.initResults = function() {
            $('#search-results').on('click', '[data-id]', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                admin.ajaxOverlay(url);
            });
        };

        this.init();
    }

    return new SearchForm();
});