function Admin (router, templating, translator)
{
  var self = this;
  var overlay = $("#overlay");
  var overlayContent = $("#overlayContent");
  var overlayMessage = $("#overlayMessage");
  var ajaxOverlaySynchronized = true;
  var loadingOverlay = null;
  var loadingOverlayMutex = 0;

  var MessageType = {
    Info: 'info',
    Error: 'error',
    Success: 'success'
  };

  /**
   * overlay
   *
   * html
   *
   * option have this keys
   * options = {
  *   init: function
  * };
   *
   * @param html
   * @param options
   * @returns void
   */
  this.overlay = function(html, options) {

    if(!options) {
      options = {};
    }

    var init = function() {

      $(document).one('keyup',function(event) {
        if(event.keyCode == 27) {
          event.stopPropagation();
          event.preventDefault();
          self.overlayClose();
        }
      });

      overlayContent.find('.close').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        self.overlayClose();
      });

      self.initTabs('#overlayContent');
    };

    var overlayStart = function() {
      overlayContent.trigger('formOpenBefore');

      overlay.fadeIn(50);
      overlayContent.append($.parseHTML(html));
      overlayContent.fadeIn(100, function() {
        overlayContent.trigger('formOpenAfter', [ overlayContent.find('form').get(0) ]);
      });
      overlayContent.animate({
        scrollTop: 0
      }, 100);

      init();
      if(options.init) {
        options.init();
      }
    };

    overlayStart();
  };

  this.overlayClose = function()
  {
    var overlayStop = function() {
      overlayContent.trigger('formCloseBefore', [overlayContent]);
      overlayContent.html('');
      overlay.fadeOut(100);
      overlayContent.fadeOut(50);
      overlayContent.trigger('formCloseAfter', [overlayContent]);
    };
    overlayStop();
  };

  /**
   * ajaxOverlay
   *
   * html
   *
   * option have this keys
   * options = {
   *   init: function
   * };
   *
   * @param url
   * @param options
   * @returns void
   */
  this.ajaxOverlay = function(url, options)
  {
    if(ajaxOverlaySynchronized) {
      ajaxOverlaySynchronized = false;
      self.openLoadingOverlay();
      $.ajax({
        url: url,
        success : function(data) {
          self.closeLoadingOverlay();
          self.overlay(data, options);
          ajaxOverlaySynchronized = true;
        },
        error : function(data) {
          self.closeLoadingOverlay();
          var message = 'error.occurred';
          if(data.status == 403) {
            message = 'error.forbidden';
          }
          self.overlayMessage(translator.trans(message), MessageType.Error);
          ajaxOverlaySynchronized = true;
        }
      });
    }

  };

  /**
   *
   * option have this keys
   * options = {
   *   submit: boolean If true the form will be submitted to the iframe overlay
   * };
   *
   * @param form
   * @param url
   * @param options
   */
  this.iframeOverlay = function(form,url,options)
  {
    if(!options) {
      options = {};
    }

    var originalAction = $(form).prop('action');
    var iframeContainer = $('#iframeContainer');
    var iframe = iframeContainer.find('iframe');

    if(options.submit) {
      $(form).prop('target','iframe');
      $(form).prop('action',url);
      $(form).submit();
    }

    iframeContainer.fadeIn(100);
    iframeContainer.find('.close').on('click',function(event) {
      event.stopPropagation();
      event.preventDefault();
      self.iframeClose(form,originalAction);
    });
  };

  this.iframeClose = function(form,originalAction) {
    var iframeContainer = $('#iframeContainer');
    var iframe = iframeContainer.find('iframe');
    iframeContainer.fadeOut(200,function() {
      iframe.prop('src','');
      $(form).prop('target','');
      $(form).prop('action',originalAction);
    });
  };

  this.overlayMessage = function(content, type)
  {
    var overlayTimeout = null;
    clearTimeout(overlayTimeout);
    if(!type) {
      type = MessageType.Info;
    }

    overlayMessage.removeClass(MessageType.Info);
    overlayMessage.removeClass(MessageType.Error);
    overlayMessage.removeClass(MessageType.Success);
    overlayMessage.addClass(type);

    overlayMessage.html(content).stop().fadeIn(150,function() {
      overlayTimeout = setTimeout(function() {
        overlayMessage.fadeOut(150);
      }, 3500);
    });
  };

  this.initTable = function() {
    $(document).on('click', '.table-container .entry-row', function() {
      var tableContainer = $(this).parents('.table-container');
      var route = tableContainer.attr('data-edit-route');
      var id = $(this).attr('data-id');
      var url = router.generate(route, { id: id });
      self.ajaxOverlay(url);
    });
  };

  this.reloadBlock = function(block, callback)
  {
    var page = block.data('block-page');
    var url = router.generate(block.data('block-table-route'), {page: page});
    self.openLoadingOverlay();
    $.ajax({
      url: url,
      success : function(data) {
        self.closeLoadingOverlay();
        block.html(data);
        self.initSortable(block);
        if(callback) {
          callback();
        }
      },
      error : function() {
        self.closeLoadingOverlay();
        self.overlayMessage(translator.trans('error.occurred') , MessageType.Error);
      }
    })
  };

  this.initActions = function() {
    $(document).on('click', '[data-action]' , function(event) {
      event.stopPropagation();
      event.preventDefault();
      var route = $(this).data('action-route');
      var link = router.generate(route);
      self.ajaxOverlay(link);
    });
  };

  this.initTabs = function(selector)
  {
    $(selector + " .tabContainer a").each(function() {
      var tab = $("#"+$(this).attr("tabId"));
      if(tab.length > 0) {
        tab.hide();
        $(this).on("click",function(e) {
          e.preventDefault();
          $(this).siblings("a").each(function() {
            var toHide = $("#"+$(this).attr("tabId"));
            toHide.hide();
            $(this).removeClass("selected");
          });
          $(this).addClass("selected");
          tab.show();
        });
      }
    });
    $(selector + " .tabContainer a:first-child").trigger("click");
  };

  this.initAfterSaveHandler = function()
  {
      $(document).on('click', '[data-button][data-type=cancel]', function() {
          self.overlayClose();
      });
  };

  this.initBlocks = function()
  {
    $('[data-block]').each(function(index, element) {
      var type = $(this).data('block-type');
      var route = $(element).data('block-table-route');
      var url = router.generate(route);
      var block = $(this);
      self.openLoadingOverlay();
      $.get(url, function (data) {
        self.closeLoadingOverlay();
        block.html(data);

        $(document).on('formSaveAfter', function() {
          var page = block.data('block-page');
          var url = router.generate(route, {page: page});
          self.reloadBlock(block);
          self.closeLoadingOverlay();
        });

        block.on('click', '[data-page]', function() {
          var page = $(this).data('page');
          block.data('block-page', page);
          var url = router.generate(route, {page: page});
          self.reloadBlock(block);
        });

        block.on('click', '[data-id]', function() {
          var id = $(this).data('id');
          var route = block.data('block-update-route');
          var url = router.generate(route, {id: id});
          self.ajaxOverlay(url);
        });

        if (block.find('[data-sortable-container]').length > 0) {
          self.initSortable(block);
        }
      }).fail(function() {
        self.closeLoadingOverlay();
      });
    });
  };

  this.initNavigation = function()
  {
    $(function(){
      $('[data-mobile-menu]').on('click', function(){
        $('[data-menu-container]').toggleClass("active");
        $('[data-content-container]').toggleClass("push");
        $(this).toggleClass("push");
      });
    });
  };

  this.initSortable = function (block) {
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

    var url = "";
    var switchToPage = -1;

    sortable.sortable({
      items: '[data-sortable-row]',
      handle: '.sortable-button',
      //distance: 5,
      opacity: 0.5,
      scroll: false,
      start: function(event, ui) {
        $('.sortable-droptarget').addClass('drag-active');
      },
      stop: function(event, ui) {
        $('.sortable-droptarget').removeClass('drag-active');

        if (switchToPage > -1) {
          sortable.sortable("cancel");
          self.openLoadingOverlay();
          $.ajax({
            url: url,
            method: 'POST',
            success: function() {
              block.data('block-page', switchToPage);
              self.reloadBlock(block);
              self.closeLoadingOverlay();
            },
            error : function() {
              self.closeLoadingOverlay();
              self.overlayMessage(translator.trans('error.occurred') , MessageType.Error);
            }
          });
        } else {
          $.ajax({
            url: url,
            method: 'POST',
            error : function() {
              self.overlayMessage(translator.trans('error.occurred') , MessageType.Error);
            }
          });
        }
      },
      sort: function(event, ui) {
        paginationPages.each(function() {
          var $this = $(this);
          if ((event.pageX >= $this.offset().left)
            && (event.pageY >= $this.offset().top)
            && (event.pageX <= $this.offset().left + $this.outerWidth(false))
            && (event.pageY <= $this.offset().top + $this.outerHeight(false)))
          {
            $this.addClass('drag-over');
          } else {
            $this.removeClass('drag-over');
          }
        });

        var found = false;
        paginationPages.each(function() {
          var $this = $(this);
          if ((event.pageX >= $this.offset().left)
            && (event.pageY >= $this.offset().top)
            && (event.pageX <= $this.offset().left + $this.outerWidth(false))
            && (event.pageY <= $this.offset().top + $this.outerHeight(false)))
          {
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

  this.openLoadingOverlay = function() {
    if (loadingOverlay == null) {
      // Init
      loadingOverlay = $('<div class="loading-overlay hidden"><i class="loading-icon icon-spinner icon-spin"></i></div>');
      $(document.body).append(loadingOverlay);
    }
    loadingOverlayMutex++;
    console.log('Mutex: ' + loadingOverlayMutex);
    if (loadingOverlayMutex == 1) {
      loadingOverlay.removeClass('hidden');
      loadingOverlay.fadeTo(300, 0.2);
    }
  };

  this.closeLoadingOverlay = function() {
    if (loadingOverlayMutex > 0) {
      loadingOverlayMutex--;
      console.log('Mutex: ' + loadingOverlayMutex);
    }
    if (loadingOverlay != null && loadingOverlayMutex == 0) {
      loadingOverlay.fadeTo(100, 0, function() {
        // If the overlay gets closed and opened immediately afterwards, it could be open again when the fadeout animation finishes.
        // So only assign the hidden class if it really is closed.
        if (loadingOverlayMutex == 0) {
          loadingOverlay.addClass('hidden');
        }
      });
    }
  };
}

