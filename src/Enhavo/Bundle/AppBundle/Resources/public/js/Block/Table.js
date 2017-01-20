define(['jquery', 'app/Admin', 'app/Router', 'app/Translator', 'urijs/URI', 'app/Form'], function($, admin, router, translator, URI, form) {
  
  function Table()
  {
    var self = this;

    this.init = function() {
      $('body').on('initBlock', function(event, data) {
        if(data.type == 'table') {
          self.initTable(data.block);
          self.initFilter(data.block);
        }
      });
    };

    this.initTable = function(block)
    {
      var $block = $(block);

      var route = $block.data('block-table-route');
      var parameters = $block.data('block-table-route-parameters');
      if(typeof parameters != 'object') {
        parameters = {};
      }
      var url = router.generate(route, parameters);

      $block.addClass('loading');
      $.get(url, function (data) {
        $block.removeClass('loading');
        var table = $.parseHTML(data);
        var $table = $(table);
        $block.find('[data-table]').html(table);

        $(document).on('formSaveAfter', function () {
          var page = $block.data('block-page');
          self.loadTable($block);
          admin.closeLoadingOverlay();
        });

        $block.on('click', '[data-page]', function () {
          var page = $(this).data('page');
          $block.data('block-page', page);
          self.loadTable($block);
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

    this.loadTable = function(block, callback) {
      var page = block.data('block-page');
      var tableRouteParameters = block.data('block-table-route-parameters');
      if(typeof tableRouteParameters != 'object') {
        tableRouteParameters = {};
      }
      tableRouteParameters.page = page;
      var url = router.generate(block.data('block-table-route'), tableRouteParameters);
      url = self.applyFilterOnUrl(block, url);
      block.addClass('loading');
      $.ajax({
        url: url,
        success: function (data) {
          block.removeClass('loading');
          var table = $.parseHTML(data);
          var $table = $(table);
          block.find('[data-table]').html(table);
          admin.initSortable($table);
          admin.initBatchActions($table);
          if (callback) {
            callback();
          }
        },
        error: function () {
          admin.closeLoadingOverlay();
          admin.overlayMessage(translator.trans('error.occurred'), admin.MessageType.Error);
        }
      });
    };

    this.initFilter = function (block) {
      form.initSelect(block);
      form.initRadioAndCheckbox(block);

      $(block).find('[data-filter-show]').click(function() {
        $(block).find('[data-filter-apply]').toggleClass('hide');
        $(block).find('[data-filters]').toggleClass('hide');

        var $this = $(this);
        if($this.data('filter-show')) {
          $this.text($this.data('filter-show-message'));
          $this.data('filter-show', false);
        } else {
          $this.text($this.data('filter-hide-message'));
          $this.data('filter-show', true);
        }
      });

      $(block).find('[data-filters] input[type=text]').keyup(function(event) {
        event.preventDefault();
        if(event.keyCode == 13){
          self.loadTable($(block));
        }
      });

      $(block).find('[data-filter-apply]').click(function(event) {
        event.preventDefault();
        self.loadTable($(block));
      });
    };

    this.applyFilterOnUrl = function(block, url) {

      var filters = [];
      block.find('[data-filter]').each(function() {
        var $input = $(this);
        if($input.attr('type') == 'checkbox') {
          filters.push({
            name: $(this).attr('name'),
            value: $input.prop('checked')
          });
        } else {
          filters.push({
            name: $(this).attr('name'),
            value: $(this).val()
          });
        }
      });
      var data = JSON.stringify(filters);
      url = URI(url).addSearch("filters", data);
      return url;
    };
  }

  var table = new Table();
  table.init();
  return table;
});