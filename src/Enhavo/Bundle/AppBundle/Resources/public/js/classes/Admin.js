function Admin (router, templating, translator)
{
  var self = this;
  var overlay = null;
  var overlayContent = null;
  var overlayMessage = null;
  var overlayIsDialog = false;
  var overlayIsOpen = false;
  var ajaxOverlaySynchronized = true;
  var loadingOverlay = null;
  var loadingOverlayMutex = 0;

  this.MessageType = {
    Info: 'info',
    Error: 'error',
    Success: 'success'
  };

  this.viewport = function() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
      a = 'client';
      e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
  };

  this.initOverlay = function() {
    overlay = $("#overlay");
    overlayContent = $("#overlayContent");
    overlayMessage = $("#overlayMessage");

    $(document).on('keyup',function(event) {
      if(event.keyCode == 27) {
        if (overlayIsOpen) {
          event.stopPropagation();
          event.preventDefault();
          self.overlayClose();
        }
      }
    });
    $(document).on('click', '#overlayContent .close', function(event) {
      if (overlayIsOpen) {
        event.stopPropagation();
        event.preventDefault();
        self.overlayClose();
      }
    });
  };

  /**
   * overlay
   *
   * html
   *
   * option have this keys
   * options = {
  *   init: function,
  *   dialog: bool
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

    if(overlay == null) {
      self.initOverlay();
    }

    var overlayStart = function() {
      overlayIsOpen = true;

      overlayContent.trigger('formOpenBefore');

      var dom = $.parseHTML(html);
      $("body, html").css("overflow-y", "hidden");
      overlay.fadeIn(50);
      overlayContent.append(dom);
      overlayContent.fadeIn(100, function() {
        overlayContent.trigger('formOpenAfter', [overlayContent.find('form').get(0)]);
        if(options.init) {
          options.init(dom);
        }
      });
      overlayContent.animate({
        scrollTop: 0
      }, 100);

      self.initTabs('#overlayContent');
    };

    overlayStart();
  };

  this.overlayClose = function()
  {
    if (!overlayIsOpen) {
      return;
    }

    var overlayStop = function() {
      if (!overlayIsDialog) {
        overlayContent.trigger('formCloseBefore', [overlayContent]);
      }

      $("body, html").css("overflow-y", "auto");
      overlayContent.html('');
      overlay.fadeOut(100);
      overlayContent.fadeOut(50);
      if (!overlayIsDialog) {
        overlayContent.trigger('formCloseAfter', [overlayContent]);
      }
      overlayIsOpen = false;
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
          self.overlayMessage(translator.trans(message), self.MessageType.Error);
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

  this.overlayMessage = function(content, type) {
    var overlayTimeout = null;
    clearTimeout(overlayTimeout);
    if (!type) {
      type = self.MessageType.Info;
    }

    if(overlayMessage == null) {
      overlayMessage = $("#overlayMessage");
    }
    overlayMessage.removeClass(self.MessageType.Info);
    overlayMessage.removeClass(self.MessageType.Error);
    overlayMessage.removeClass(self.MessageType.Success);
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
        self.initBatchActions(block);
        if(callback) {
          callback();
        }
      },
      error : function() {
        self.closeLoadingOverlay();
        self.overlayMessage(translator.trans('error.occurred') , self.MessageType.Error);
      }
    })
  };

  this.initActions = function() {
    $(document).on('click', '[data-action=""]' , function(event) {
      event.stopPropagation();
      event.preventDefault();
      var route = $(this).data('action-route');
      var parameters = $(this).data('action-route-parameters');
      if(!parameters) {
        parameters = {};
      }
      var link = router.generate(route, parameters);
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
          if(route != undefined){
            var url = router.generate(route, {id: id});
            self.ajaxOverlay(url);
          }
        });

        self.initSortable(block);
        self.initBatchActions(block);
      }).fail(function() {
        self.closeLoadingOverlay();
      });
    });
  };

  this.initNavigation = function()
  {
    $('[data-mobile-menu]').on('click', function(){
      $('[data-menu-container]').toggleClass("active");
      $('[data-content-container]').toggleClass("push");
      $('#user-menu').toggleClass("push");
      $(this).toggleClass("push");
    });
  };

  this.initUserMenu = function()
  {
    var userMenuActive = false;
    var buttonWidth = $("[data-open-usermenu]").width() + 20;

    $("[data-open-usermenu]").css("width", buttonWidth);

    $("[data-open-usermenu]").on("click", function(){
      $(this).toggleClass("clicked");
      $("[data-usermenu-link]").fadeToggle(100);
      $("#user-menu").toggleClass("background");

      var menuWidth = $("[data-user-menu]").innerWidth();

      if (userMenuActive) {
        userMenuActive = false;
        $(this).css('right', '20px');
      } else {
        userMenuActive = true;
        $(this).css('right', menuWidth   + 'px');
      }

      var dimensions = self.viewport();
      if (dimensions.width <= 767) {
        $("#user-menu").toggleClass("push-left");

        if (userMenuActive) {
          userMenuActive = false;
          $(this).css('right', '200');
        } else {
          userMenuActive = true;
          $(this).css('right', menuWidth + 'px');
        }

      }
      if (dimensions.width <= 600) {
        $('[data-mobile-menu]').toggle()
        $('[data-content-container]').toggleClass("push-left");

      }
    });
  };

  this.initDescriptionTextPosition = function()
  {
    $(window).on("load resize",function() {
      var desc = $("[data-description-text]");
      var wh = $(window).height()
      var menuHeight = $("#menu-main").height() + 240;

      if(menuHeight > wh) {
        $(desc).css({"position" : "relative", "bottom" : "15px"})
      } else
      {
        $(desc).css({"position" : "absolute", "bottom" : "25px"})
      }
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

    $('.sortable-button').click(function(event) {
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
              self.overlayMessage(translator.trans('error.occurred') , self.MessageType.Error);
            }
          });
        } else {
          $.ajax({
            url: url,
            method: 'POST',
            error : function() {
              self.overlayMessage(translator.trans('error.occurred') , self.MessageType.Error);
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

  this.confirm = function(message, callback) {
    var html = $('#confirmDialog').html();
    html = html.replace('__message__', message);
    self.overlay(html, {
      init: function(html) {
        $(html).find('[data-dialog-confirm]').click(function(event) {
          event.preventDefault();
          self.overlayClose();
          callback();
        });
      }
    });
  };

  this.alert = function(message) {
    var html = $('#alertDialog').html();
    html = html.replace('__message__', message);
    self.overlay(html);
  };

  this.openLoadingOverlay = function() {
    if (loadingOverlay == null) {
      // Init
      loadingOverlay = $('<div class="loading-overlay hidden"><i class="loading-icon icon-spinner icon-spin"></i></div>');
      $(document.body).append(loadingOverlay);
    }
    loadingOverlayMutex++;
    if (loadingOverlayMutex == 1) {
      loadingOverlay.removeClass('hidden');
      loadingOverlay.fadeTo(300, 0.75);
    }
  };

  this.closeLoadingOverlay = function() {
    if (loadingOverlayMutex > 0) {
      loadingOverlayMutex--;
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

  this.initBatchActions = function(block) {
    if (block.find('.has-batch-actions').length == 0) {
      return;
    }

    var selectAll = block.find('.batch-select-all input');
    var selectRows = block.find('.entry-row .batch-checkbox-wrapper input');
    var actionSelect = block.find('[data-batch-actions-select]');
    var submit = block.find('[data-batch-actions-submit]');
    var form = block.find('[data-batch-action-form ]');

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

    block.find('.batch-checkbox-wrapper').click(function(event) {
      event.stopPropagation();
      $(this).find('input').iCheck('toggle');
    });

    actionSelect.select2();

    var optimitzeSelectSize = function() {
      //Calculate size of biggest element in select
      var styleReference = block.find('.batch-actions-select-wrapper .select2-chosen');
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
      block.find('.batch-actions-select-wrapper .select2-container').css('width', sizeTester.width() + 60);
      $(sizeTester).remove();
    };

    optimitzeSelectSize();

    submit.on('click', function(event) {
      event.preventDefault();

      var type = getBatchType();
      if (type == 0) {
        self.alert(submit.data('message-no-action-selected'));
        return;
      }

      var ids = getBatchIds();
      if (ids.length == 0) {
        self.alert(submit.data('message-none-selected'));
        return;
      }

      self.confirm(getBatchConfirmMessage(), function() {
        self.openLoadingOverlay();
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
            self.closeLoadingOverlay();
            self.reloadBlock(block);
          },
          error : function() {
            self.closeLoadingOverlay();
            self.overlayMessage(translator.trans('error.occurred') , self.MessageType.Error);
          }
        });
      })
    });
  };
}
