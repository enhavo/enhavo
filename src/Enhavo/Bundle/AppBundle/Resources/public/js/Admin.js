define(['jquery', 'app/Router', 'app/Templating', 'app/Translator', 'icheck', 'select2'], function($, router, templating, translator, iCheck, select2) {

 var Admin = function() {
    var self = this;
    var overlay = null;
    var overlayContent = null;
    var overlayMessage = null;
    var overlayIsDialog = false;
    var overlayIsOpen = false;
    var iframeOverlayIsOpen = false;
    var ajaxOverlaySynchronized = true;
    var loadingOverlay = null;
    var loadingOverlayMutex = 0;

    this.MessageType = {
      Info: 'info',
      Error: 'error',
      Success: 'success'
    };

    this.viewport = function () {
      var e = window, a = 'inner';
      if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
      }
      return {width: e[a + 'Width'], height: e[a + 'Height']};
    };

    this.initOverlay = function () {
      overlay = $("#overlay");
      overlayContent = $("#overlayContent");
      overlayMessage = $("#overlayMessage");

      $(document).on('click', '#overlayContent .close', function (event) {
        if (overlayIsOpen) {
          event.stopPropagation();
          event.preventDefault();
          self.overlayClose();
        }
      });
    };

    this.initEscButton = function() {
      $(document).on('keyup', function (event) {
        if (event.keyCode == 27) { // esc button
          if (iframeOverlayIsOpen) {
            event.stopPropagation();
            event.preventDefault();
            self.iframeClose();
            return;
          }
          if (overlayIsOpen) {
            event.stopPropagation();
            event.preventDefault();
            self.overlayClose();
          }
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
    this.overlay = function (html, options) {

      if (!options) {
        options = {};
      }

      if (overlay == null) {
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

    this.overlayClose = function () {
      if (!overlayIsOpen) {
        return;
      }

      var overlayStop = function () {
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
    this.ajaxOverlay = function (url, options) {
      if (ajaxOverlaySynchronized) {
        ajaxOverlaySynchronized = false;
        self.openLoadingOverlay();
        $.ajax({
          url: url,
          success: function (data) {
            self.closeLoadingOverlay();
            self.overlay(data, options);
            ajaxOverlaySynchronized = true;
          },
          error: function (data) {
            self.closeLoadingOverlay();
            var message = 'error.occurred';
            if (data.status == 403) {
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
    this.iframeOverlay = function (form, url, options) {
      if (!options) {
        options = {};
      }

      iframeOverlayIsOpen = true;

      var originalAction = $(form).prop('action');
      var iframeContainer = $('#iframeContainer');
      var iframe = iframeContainer.find('iframe');

      if (options.submit) {
        $(form).prop('target', 'iframe');
        $(form).prop('action', url);
        $(form).submit();
      }

      iframeContainer.fadeIn(100);
      iframeContainer.find('.close').on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();
        self.iframeClose(form, originalAction);
      });
    };

    this.iframeClose = function (form, originalAction) {
      var iframeContainer = $('#iframeContainer');
      var iframe = iframeContainer.find('iframe');
      iframeContainer.fadeOut(200, function () {
        iframe.prop('src', '');
        $(form).prop('target', '');
        $(form).prop('action', originalAction);
        iframeOverlayIsOpen = false;
      });
    };

    this.overlayMessage = function (content, type) {
      var overlayTimeout = null;
      clearTimeout(overlayTimeout);
      if (!type) {
        type = self.MessageType.Info;
      }

      if (overlayMessage == null) {
        overlayMessage = $("#overlayMessage");
      }
      overlayMessage.removeClass(self.MessageType.Info);
      overlayMessage.removeClass(self.MessageType.Error);
      overlayMessage.removeClass(self.MessageType.Success);
      overlayMessage.addClass(type);


      overlayMessage.html(content).stop().fadeIn(150, function () {
        overlayTimeout = setTimeout(function () {
          overlayMessage.fadeOut(150);
        }, 3500);
      });
    };

    this.initActions = function () {
      $(document).on('click', '[data-action=""]', function (event) {
        event.stopPropagation();
        event.preventDefault();
        var route = $(this).data('action-route');
        var parameters = $(this).data('action-route-parameters');
        if (!parameters) {
          parameters = {};
        }
        var link = router.generate(route, parameters);
        self.ajaxOverlay(link);
      });
    };

    this.initTabs = function (selector) {
      $(selector + " .tabContainer a").each(function () {
        var tab = $("#" + $(this).attr("tabId"));
        if (tab.length > 0) {
          tab.hide();
          $(this).on("click", function (e) {
            e.preventDefault();
            $(this).siblings("a").each(function () {
              var toHide = $("#" + $(this).attr("tabId"));
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

    this.initAfterSaveHandler = function () {

    };

    this.initBlocks = function () {
      $('[data-block]').each(function (index, element) {
        var blockApp = $(this).data('block-app');
        var type = $(this).data('block-type');
        if(blockApp) {
          require([blockApp], function() {
            $('body').trigger('initBlock', {
              type: type,
              block: element
            });
          });
        } else {
          $('body').trigger('initBlock', {
            type: type,
            block: element
          });
        }
      });
    };
   
    this.initNavigation = function () {
      $('[data-mobile-menu]').on('click', function () {
        $('[data-menu-container]').toggleClass("active");
        $('[data-content-container]').toggleClass("push");
        $('#user-menu').toggleClass("push");
        $(this).toggleClass("push");
      });
    };

    this.initUserMenu = function () {
      var userMenuActive = false;
      var buttonWidth = $("[data-open-usermenu]").width() + 20;

      $("[data-open-usermenu]").css("width", buttonWidth);

      $("[data-open-usermenu]").on("click", function () {
        $(this).toggleClass("clicked");
        $("[data-usermenu-link]").fadeToggle(100);
        $("#user-menu").toggleClass("background");

        var menuWidth = $("[data-user-menu]").innerWidth();

        if (userMenuActive) {
          userMenuActive = false;
          $(this).css('right', '20px');
        } else {
          userMenuActive = true;
          $(this).css('right', menuWidth + 'px');
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

    this.initDescriptionTextPosition = function () {
      var setDescriptionTextPosition = function() {
        var desc = $("[data-description-text]");
        var wh = $(window).height()
        var menuHeight = $("#menu-main").height() + 240;

        if (menuHeight > wh) {
          $(desc).css({"position": "relative", "bottom": "15px"})
        } else {
          $(desc).css({"position": "absolute", "bottom": "25px"})
        }
      };
      $(window).on("load resize",setDescriptionTextPosition);
      setDescriptionTextPosition();
    };

    this.openLoadingOverlay = function () {
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

    this.closeLoadingOverlay = function () {
      if (loadingOverlayMutex > 0) {
        loadingOverlayMutex--;
      }
      if (loadingOverlay != null && loadingOverlayMutex == 0) {
        loadingOverlay.fadeTo(100, 0, function () {
          // If the overlay gets closed and opened immediately afterwards, it could be open again when the fadeout animation finishes.
          // So only assign the hidden class if it really is closed.
          if (loadingOverlayMutex == 0) {
            loadingOverlay.addClass('hidden');
          }
        });
      }
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

  };

  return new Admin;
});
