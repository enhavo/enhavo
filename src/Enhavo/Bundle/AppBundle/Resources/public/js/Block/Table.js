define(['jquery', 'app/Admin', 'app/Router', 'app/Translator'], function($, admin, router, translator) {
  
  function Table()
  {
    var self = this;

    this.init = function() {
      $('body').on('initBlock', function(event, data) {
        if(data.type == 'table') {
          initTable(data.block);
        }
      });

      var reloadTable = function(block, callback) {
        var page = block.data('block-page');
        var tableRouteParameters = block.data('block-table-route-parameters');
        if(typeof tableRouteParameters != 'object') {
          tableRouteParameters = {};
        }
        tableRouteParameters.page = page;
        var url = router.generate(block.data('block-table-route'), tableRouteParameters);
        block.addClass('loading');
        $.ajax({
          url: url,
          success: function (data) {
            block.removeClass('loading');
            block.html(data);
            admin.initSortable(block);
            admin.initBatchActions(block);
            if (callback) {
              callback();
            }
          },
          error: function () {
            admin.closeLoadingOverlay();
            admin.overlayMessage(translator.trans('error.occurred'), admin.MessageType.Error);
          }
        })
      };

      var initTable = function(block) {
        var $block = $(block);

        var route = $block.data('block-table-route');
        var parameters = $block.data('block-table-route-parameters');
        if(typeof parameters != 'object') {
          parameters = {};
        }
        var url = router.generate(route, parameters);

        self.initFilter($block);

        $block.addClass('loading');
        $.get(url, function (data) {
          $block.removeClass('loading');
          var table = $.parseHTML(data);
          var $table = $(table);
          $block.find('[data-table]').html(table);

          $(document).on('formSaveAfter', function () {
            var page = $block.data('block-page');
            reloadTable($block);
            admin.closeLoadingOverlay();
          });

          $block.on('click', '[data-page]', function () {
            var page = $(this).data('page');
            $block.data('block-page', page);
            reloadTable($block);
          });

          $block.on('click', '[data-id]', function (event) {
            var $target = $(event.target);
            if($target.is('a')) {
              return true;
            }
            event.preventDefault();
            var id = $(this).data('id');
            var route = $block.data('block-update-route');
            var parameters = $block.data('block-update-route-parameters');
            if(typeof parameters != 'object') {
              parameters = {};
            }
            parameters.id = id;
            if (route != undefined) {
              var url = router.generate(route, parameters);
              admin.ajaxOverlay(url);
            }
          });

          admin.initSortable($table);
          admin.initBatchActions($table);
        }).fail(function () {
          admin.closeLoadingOverlay();
        });
      };
    };

    this.initFilter = function (block) {
      block.find('[data-filter]').each(function() {
        $(this).find('[data-filter-boolean]').iCheck({
          checkboxClass: 'icheckbox-esperanto',
          radioClass: 'icheckbox-esperanto'
        });
      });
    };
  }

  var table = new Table();
  table.init();
  return table;
});