/*
selector: jquery element(s), like $('.fullscreen-element.')

if there is a parent of an element having attribute 'data-fullscreen-container' set,
it will be fill out that parent. else $(window) will be used as its parent.
 */
function esperanto_fullscreen(selector) {
  this.elements = selector;
  var self = this;

  this.init = function() {
    this.expandAll();
    $(window).on('resize',function() {
      self.expandAll();
    });
  },
  this.expandAll = function() {
    self.elements.each(function() {
      self.expand($(this));
    });
  },
  this.getContainerOfElement = function(element) {
    var container = element.parents('[data-fullscreen-container]');
    if(!container.length) {
      container = $(window);
    }
    return container;
  },
  this.expand = function(element) {
    if(element.data('width') && element.data('height')) {
      this.setElementCSS(element);
      this.setElementPosition(element,element.data('width'),element.data('height'));
      this.replacePreviewWithHighRes(element);
    } else {
      var img = new Image();
      $(img).one('load', function () {
        setTimeout(function () {
          self.setElementCSS(element);
          self.setElementPosition(element,img.width,img.height);
          self.replacePreviewWithHighRes(element);
        }, 1);
      });
      img.src = element.prop('src');
    }
  },
  this.setElementCSS = function(element) {
    var container = element.parents('[data-fullscreen-container]');
    if(!container.length) {
      element.removeClass('esperanto-fullscreen-within');
      element.addClass('esperanto-fullscreen');
    } else {
      element.removeClass('esperanto-fullscreen');
      element.addClass('esperanto-fullscreen-within');
    }
  },
  this.setElementPosition = function(element,elementwidth,elementheight) {
    var ratio = elementwidth / elementheight;
    var container = self.getContainerOfElement(element);
    var containerHeight = container.height();
    var containerWidth = container.width();
    var containerRatio = containerWidth / containerHeight;

    if(containerRatio > ratio) {
      element.width(containerWidth+150);
      element.height((containerWidth+150) / ratio);
      element.css('top', (containerHeight - element.height()) / 2);
      element.css('left', 0);
    } else {
      element.height(containerHeight);
      element.width(containerHeight * ratio);
      element.css('left', (containerWidth - element.width()) / 2);
      element.css('top', 0);
    }
  },
  this.replacePreviewWithHighRes = function(element) {
    if(element.data('large-src')) {
      var img = new Image();
      $(img).one('load', function () {
        element.get(0).src = element.data('large-src');
      });
      img.src = element.data('large-src');
    }
  },
  this.init();
}