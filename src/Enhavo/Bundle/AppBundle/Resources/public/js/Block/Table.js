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

    this.getQueryParameters = function(variable)
    {
      var query = window.location.search.substring(1);
      var vars = query.split('&');
      for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == variable) {
          return decodeURIComponent(pair[1]);
        }
      }
    };

    this.initTable = function(block)
    {
      var $block = $(block);

      // open if query is set
      var id = this.getQueryParameters('id');
      if(id) {
        var updateRoute = $block.data('block-update-route');
        var updateParameters = $block.data('block-update-route-parameters');
        if(typeof updateParameters != 'object') {
          updateParameters = {};
        }
        updateParameters.id = id;
        admin.ajaxOverlay(router.generate(updateRoute, updateParameters));
      }

      // update table
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

          // check if link
          var isLink = false;
          var isInputField = false;
          $target.parentsUntil('[data-id]').each(function() {
            if($(this).is('a')) {
              isLink = true;
            }
          });
          if($target.is('a')) {
            isLink = true;
          }
          if($target.is('input')) {
            isInputField = true;
          }
          if(isLink || isInputField) {
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

            var pageUrl = '?id=' + id;
            window.history.pushState('', '', pageUrl);

            admin.ajaxOverlay(url);
          }
        });

        self.initSortable($table);
        self.initBatchActions(table);
        $(document).trigger('enhavoTableLoaded', [$block]);
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
          self.initSortable($table);
          self.initBatchActions(table);
          if (callback) {
            callback();
          }
          $(document).trigger('enhavoTableLoaded', [block]);
        },
        error: function () {
          admin.closeLoadingOverlay();
          admin.overlayMessage(translator.trans('error.occurred'), admin.MessageType.Error);
        }
      });
    };

    this.initFilter = function (block) {

      $(block).find('[data-filter-select]').each(function(index, element) {
        form.initSelect(element);
      });

      $(block).find('[data-filter-checkbox]').each(function(index, element) {
        form.initRadioAndCheckbox(element);
      });

      $(block).find('[data-filter-auto-complete-entity]').each(function(index, element) {
        form.initAutoComplete(element);
      });

      $(block).find('[data-filter-text] input[type=text]').keyup(function(event) {
        event.preventDefault();
        if(event.keyCode == 13){
          self.applyFilter($(block));
        }
      });

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

      $(block).find('[data-filter-apply]').click(function(event) {
        event.preventDefault();
        self.applyFilter($(block));
      });
    };

    this.applyFilter = function($block) {
      $block.data('block-page', 1);
      self.loadTable($block);

      var filterActive = false;
      $block.find('[data-filter] [data-filter-input]').each(function(index, element) {
        if($(element).is('input[type=checkbox]')) {
          if($(element).prop('checked')) {
            filterActive = true;
            return false;
          }
          return;
        }
        if($(element).val() != '') {
          filterActive = true;
          return false;
        }
      });

      if(filterActive) {
        $block.data('block-table-filter-active', true);
      } else {
        $block.data('block-table-filter-active', false);
      }
    };

    this.applyFilterOnUrl = function(block, url) {

      var filters = [];
      block.find('[data-filter] [data-filter-input]').each(function() {
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

    this.initBatchActions = function(block)
    {
      var $block = $(block);

      if ($block.find('.has-batch-actions').length == 0) {
        return;
      }

      var selectAll = $block.find('.batch-select-all input');
      var selectRows = $block.find('.entry-row .batch-checkbox-wrapper input');
      var actionSelect = $block.find('[data-batch-actions-select]');
      var submit = $block.find('[data-batch-actions-submit]');
      var form = $block.find('[data-batch-action-form]');

      if (selectRows.length == 0) {
        return;
      }

      var getBatchIds = function() {
        var ids = [];
        form.parent().find('[data-batch-selection]:checked').each(function() {
          ids.push($(this).val());
        });
        return ids;
      };

      var getBatchType = function() {
        return actionSelect.val();
      };

      var getBatchConfirmMessage = function() {
        return actionSelect.find('option:selected').data('confirm-message');
      };

      selectRows.iCheck({
        checkboxClass: 'icheckbox-esperanto'
      });

      selectAll.iCheck({
        checkboxClass: 'icheckbox-esperanto'
      }).on('ifChecked', function() {
        selectRows.iCheck('check')
      }).on('ifUnchecked', function() {
        selectRows.iCheck('uncheck')
      });

      $block.find('.batch-checkbox-wrapper').click(function(event) {
        event.stopPropagation();
        $(this).find('input').iCheck('toggle');
      });

      actionSelect.select2();

      var optimitzeSelectSize = function() {
        //Calculate size of biggest element in select
        var styleReference = $block.find('.batch-actions-select-wrapper .select2-chosen');
        var sizeTester = $('<div style="position:absolute;visibility:hidden;width:auto;height:auto;white-space:nowrap;'
          + 'font-family:' + styleReference.css('font-family')
          + 'font-size:' + styleReference.css('font-size')
          + 'font-weight' + styleReference.css('font-weight')
          + '"></div>');
        var sizeTesterHtml = '';
        actionSelect.children('option').each(function() {
          sizeTesterHtml += $(this).html() + '<br />';
        });
        sizeTester.html(sizeTesterHtml);
        $(document.body).append(sizeTester);
        $block.find('.batch-actions-select-wrapper .select2-container').css('width', sizeTester.width() + 60);
        $(sizeTester).remove();
      };

      optimitzeSelectSize();

      submit.on('click', function(event) {
        event.preventDefault();

        var type = getBatchType();
        if (type == 0) {
          admin.alert(submit.data('message-no-action-selected'));
          return;
        }

        var ids = getBatchIds();
        if (ids.length == 0) {
          admin.alert(submit.data('message-none-selected'));
          return;
        }

        admin.confirm(getBatchConfirmMessage(), function() {
          $block.parents('[data-block]').addClass('loading');
          var url = form.attr('action');
          var data = {
            ids: ids,
            type: type
          };

          $.ajax({
            url: url,
            data: data,
            method: 'POST',
            success: function() {
              $block.parents('[data-block]').removeClass('loading');
              self.loadTable($block.parents('[data-block]'));
            },
            error : function() {
              admin.closeLoadingOverlay();
              admin.overlayMessage(translator.trans('error.occurred') , self.MessageType.Error);
            }
          });
        })
      });
    };

    this.initSortable = function (block) {
      if (block.find('[data-sortable-container]').length == 0) {
        return;
      }
      var sortable = block.find('[data-sortable-container]');
      var paginationBlock = block.find('.pagination');
      var paginationPages = paginationBlock.find('a:not(.selected)');
      var moveAfterRoute = block.find('[data-move-after-route]').data('move-after-route');
      var moveToPageRoute = block.find('[data-move-to-page-route]').data('move-to-page-route');
      var currentPage = block.find(".pagination > .selected");
      if (currentPage.length > 0) {
        currentPage = currentPage.data("page");
      } else {
        currentPage = 1;
      }
      paginationPages.addClass('sortable-droptarget');


      $('.sortable-button').mousedown(function (event) {
        var filterActive = block.parents('[data-block]').data('block-table-filter-active');
        if(filterActive) {
          admin.alert('Sorting not available while filter active');
          event.stopPropagation();
          event.preventDefault();
        }
      });

      $('.sortable-button').click(function (event) {
        // Prevent row onclick for sort button
        event.stopPropagation();
      });

      var url = "";
      var switchToPage = -1;

      sortable.sortable({
        items: '[data-sortable-row]',
        handle: '.sortable-button',
        //distance: 5,
        opacity: 0.5,
        scroll: false,

        start: function (event, ui) {
          $('.sortable-droptarget').addClass('drag-active');
        },
        stop: function (event, ui) {
          $('.sortable-droptarget').removeClass('drag-active');

          if (switchToPage > -1) {
            sortable.sortable("cancel");
            $.ajax({
              url: url,
              method: 'POST',
              success: function () {
                block.data('block-page', switchToPage);
                self.loadTable(block.parents('[data-block]'));
              },
              error: function () {
                self.overlayMessage(translator.trans('error.occurred'), self.MessageType.Error);
              }
            });
          } else {
            $.ajax({
              url: url,
              method: 'POST',
              error: function () {
                self.overlayMessage(translator.trans('error.occurred'), self.MessageType.Error);
              }
            });
          }
        },
        sort: function (event, ui) {
          paginationPages.each(function () {
            var $this = $(this);
            if ((event.pageX >= $this.offset().left)
              && (event.pageY >= $this.offset().top)
              && (event.pageX <= $this.offset().left + $this.outerWidth(false))
              && (event.pageY <= $this.offset().top + $this.outerHeight(false))) {
              $this.addClass('drag-over');
            } else {
              $this.removeClass('drag-over');
            }
          });

          var found = false;
          paginationPages.each(function () {
            var $this = $(this);
            if ((event.pageX >= $this.offset().left)
              && (event.pageY >= $this.offset().top)
              && (event.pageX <= $this.offset().left + $this.outerWidth(false))
              && (event.pageY <= $this.offset().top + $this.outerHeight(false))) {
              url = router.generate(moveToPageRoute, {id: ui.item.data("id"), page: $this.data("page"), top: 1});
              switchToPage = $this.data("page");
              found = true;
            }
          });
          if (!found) {
            var previousElement = ui.placeholder.prev();
            if (previousElement.length > 0 && previousElement.hasClass('ui-sortable-helper')) {
              previousElement = previousElement.prev();
            }
            if (previousElement.length == 0) {
              url = router.generate(moveToPageRoute, {id: ui.item.data("id"), page: currentPage, top: 1});
              switchToPage = -1;
            } else {
              url = router.generate(moveAfterRoute, {id: ui.item.data("id"), target: previousElement.data('id')});
              switchToPage = -1;
            }
          }
        }
      });
    };
  }

  var table = new Table();
  table.init();
  return table;
});