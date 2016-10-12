function Search()
{
  var self = this;

  this.init = function () {
    self.initSearchForm();
  };

  this.initSearchForm = function() {
    $('[enhavo_search_submit]').on('submit', function (event) {
      event.preventDefault();
      var form = $(this);
      var url = $(this).attr('action');
      var data = $(this).serialize();

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
      var route = $(this).data('update-route');
      var url = router.generate(route, {id: id});
      admin.ajaxOverlay(url);
    });
  };

  this.init();
}

new Search();