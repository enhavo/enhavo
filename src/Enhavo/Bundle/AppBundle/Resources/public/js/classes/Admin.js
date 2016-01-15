function Admin (router, templating, translator)
{
  var self = this;
  var overlay = $("#overlay");
  var overlayContent = $("#overlayContent");
  var overlayMessage = $("#overlayMessage");
  var ajaxOverlaySynchronized = true;

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
      $.ajax({
        url: url,
        success : function(data) {
          self.overlay(data, options);
          ajaxOverlaySynchronized = true;
        },
        error : function(data) {
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
    $.ajax({
      url: url,
      success : function(data) {
        block.html(data);
        if(callback) {
          callback();
        }
      },
      error : function() {
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
      $.get(url, function (data) {
        block.html(data);

        $(document).on('formSaveAfter', function() {
          var page = block.data('block-page');
          var url = router.generate(route, {page: page});
          self.reloadBlock(block);
          //self.reloadBlock(block, url);
        });

        block.on('click', '[data-page]', function() {
          var page = $(this).data('page');
          block.data('block-page', page);
          var url = router.generate(route, {page: page});
          self.reloadBlock(block);
          //self.reloadBlock(block, url);
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
    var animationRunning = false;
    var currentAjaxHandle = null;
    var moveUpUrl = router.generate(block.find('[data-move-up-route]').data('move-up-route'));
    var moveDownUrl = router.generate(block.find('[data-move-down-route]').data('move-down-route'));

    block.on('click', '[data-sortable-up-button]', function(event) {
      event.preventDefault();
      event.stopPropagation();

      moveElement($(this), true);
    });

    block.on('click', '[data-sortable-down-button]', function(event) {
      event.preventDefault();
      event.stopPropagation();

      moveElement($(this), false);
    });

    var moveElement = function(element, directionUp) {
      if (animationRunning) {
        return;
      }
      var item = element.parents('[data-sortable-row]');
      var container = item.parents('[data-sortable-container]');
      var index = container.children().index(item);
      var firstOrLastElement = false;
      if (directionUp) {
        firstOrLastElement = index <= 0;
      } else {
        firstOrLastElement = index >= (container.children().size() - 1);
      }

      sendMoveAjax(element, directionUp, firstOrLastElement);

      if(!firstOrLastElement) {
        // Play animation
        animationRunning = true;
        var otherItem;
        if (directionUp) {
          otherItem = container.children().get(index - 1);
        } else {
          otherItem = container.children().get(index + 1);
        }

        var targetPosition = $(otherItem).position().top;
        var animPosition = Math.abs($(otherItem).position().top - item.position().top);

        item.css('left', item.position().left)
          .css('top', item.position().top)
          .css('width', item.outerWidth())
          .css('height', item.outerHeight())
          .css('position', 'absolute');

        $(otherItem).after('<div class="entry-row" style="visibility: hidden;" data-sortable-animation-dummy>&nbsp;</div>').css('visibility', 'hidden');

        function animFrame() {
          animPosition -= 5;

          if (animPosition <= 0) {
            clearInterval(animHandle);

            item.css('left', 0)
              .css('top', 0)
              .css('height', 'auto')
              .css('width', 'auto')
              .css('position', 'static');
            $(otherItem).css('visibility', 'visible');
            if (directionUp) {
              $(otherItem).before(item);
            } else {
              $(otherItem).after(item);
            }
            block.find('[data-sortable-animation-dummy]').remove();
            animationRunning = false;
          } else {
            var position;
            if (directionUp) {
              position = targetPosition + animPosition;
            } else {
              position = targetPosition - animPosition;
            }
            item.css('top', position);
          }
        }
        var animHandle = setInterval(animFrame, 10);
      }
    };

    var sendMoveAjax = function(element, directionUp, paginateReload) {
      if (currentAjaxHandle != null) {
        currentAjaxHandle.abort();
        currentAjaxHandle = null;
      }
      var id = element.parents('[data-sortable-row]').data('id');
      var url;
      if (directionUp) {
        url = moveUpUrl;
      } else {
        url = moveDownUrl;
      }

      currentAjaxHandle = $.ajax({
        type: "POST",
        url: url,
        data: {id: id}
      }).done(function(result) {
        if (!result['success'] === true) {
          self.overlayMessage(translator.trans('error.occurred'), MessageType.Error);
          // Error saving order, reload block to display current order
          self.reloadBlock(block);
        } else {
          if (paginateReload) {
            if (!result['firstOrLastElement']) {
              var page = block.data('block-page');
              if (directionUp) {
                page--;
              } else {
                page++;
              }
              block.data('block-page', page);
              self.reloadBlock(block);
            }
          }
        }
      }).fail(function(status) {
        if (!(status['statusText'] == 'abort')) {
          self.overlayMessage(translator.trans('error.occurred'), MessageType.Error);
          // Error saving order, reload block to display current order
          self.reloadBlock(block);
        }
      }).always(function() {
        currentAjaxHandle = null;
      });
    };
  };
}

