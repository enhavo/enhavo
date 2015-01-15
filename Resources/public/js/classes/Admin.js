function Admin (router, templating, translator)
{
  var self = this;
  var overlay = $("#overlay");
  var overlayContent = $("#overlayContent");
  var overlayMessage = $("#overlayMessage");
  var ajaxOverlaySynchornized = true;

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


      overlayContent.find('.close').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        self.overlayClose();
      });

      self.initTabs('#overlayContent');

      /*
      form.initTinymce('#overlayContent');
      form.contentForm('#overlayContent');
      form.handleCheckboxes('#overlayContent');
      form.handleRadioBoxes('#overlayContent');
      */
    };

    var overlayStart = function() {
      overlayContent.trigger('formOpenBefore');

      overlay.fadeIn(200);
      overlayContent.append($.parseHTML(html));
      overlayContent.fadeIn(350, function() {
        overlayContent.trigger('formOpenAfter', [ overlayContent.find('form').get(0) ]);
      });
      overlayContent.animate({
        scrollTop: 0
      }, 500, "swing");

      init();
      if(options.init) {
        options.init();
      }
    };

    overlayStart();
  };

  this.overlayClose = function() {

    var overlayStop = function() {
      overlayContent.trigger('formCloseBefore', [overlayContent]);
      overlayContent.html('');
      overlay.fadeOut(350);
      overlayContent.fadeOut(200);
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
    if(ajaxOverlaySynchornized) {
      ajaxOverlaySynchornized = false;
      $.ajax({
        url: url,
        success : function(data) {
          self.overlay(data, options);
          ajaxOverlaySynchornized = true;
        },
        error : function(data) {
          var message = 'error.occurred';
          if(data.status == 403) {
            message = 'error.forbidden';
          }
          self.overlayMessage(translator.trans(message), MessageType.Error);
          ajaxOverlaySynchornized = true;
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

    iframeContainer.fadeIn(300);
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

    overlayMessage.html(content).stop().fadeIn(200,function() {
      overlayTimeout = setTimeout(function() {
        overlayMessage.fadeOut(200);
      }, 3500);
    });
  };

  this.initPagination = function(route)
  {
    $('.pagination a').click(function() {
      var page = $(this).attr('page');
      var link = router.generate(route, { page: page });
      $(this).parent().find('a.selected').removeClass('selected');
      $(this).addClass('selected');
      self.updateTable(link);
    });
  };

  this.initTable = function() {
    $(document).on('click', '.table-container .entry-row', function() {
      var route = $('.table-container').attr('data-edit-route');
      var id = $(this).attr('data-id');
      var url = router.generate(route, { id: id });
      self.ajaxOverlay(url);
    });
  };

  this.updateTable = function(url, callback)
  {
    if(!url) {
      url = router.generate($('.table-container').attr('data-refresh-route'));
    }

    $.ajax({
      url: url,
      success : function(data) {
        $('.table-container').html(data);
        if(callback) {
          callback();
        }
      },
      error : function() {
        self.overlayMessage(translator.trans('error.occurred') , MessageType.Error);
      }
    })
  };

  this.initTopButton = function(selector, options, route, parameters) {
    $(document).on('click', selector , function(event) {
      event.stopPropagation();
      event.preventDefault();
      var link = router.generate(route, parameters);
      self.ajaxOverlay(link, options);
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
    $(document).on('formSaveAfter', function() {
      self.updateTable();
      self.overlayClose();
    });
  };
}