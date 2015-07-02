/*
  selector: jquery element(s), like $('[data-scrollto]')

  if element has data-scrollto set, its value will be used as target selector (can be any jquery selector), i.e. $(element.data('scrollto'))
  otherwise the elements href-attribute will be used as jquery selector

  options:
  duration: define, how long the scroll animation will take (in milliseconds) (default 1500)
  easing: use any easing-function, maybe u will need to include jquery-ui lib for some easing functions (default "linear")
  queue: queue animate event (bool) (default false)
  spacing_top: offset to top window border in px (default 0)
  complete: define function to be executed after scrolling has finished (default null)
 */
function enhavo_scrollto(selector,options) {
  var defaultoptions = {duration:1500,easing:'linear',queue:false,spacing_top:0,complete:null};
  this.options = $.extend({},defaultoptions,options);
  this.elements = selector;
  var self = this;

  this.init = function() {
    this.initClick();
  },
  this.initClick = function() {
    self.elements.on('click',function(e) {
      e.preventDefault();
      var scrollTopY = 0;
      var selfElement = this;
      if($(this).data('scrollto')) {
        scrollTopY = $($(this).data('scrollto')).offset().top;
      } else if($(this).attr('href') && $(this).attr('href') != '#') {
        scrollTopY = $($(this).attr('href')).offset().top;
      }

      if(scrollTopY - self.options.spacing_top > 0) {
        scrollTopY -= self.options.spacing_top;
      }

      $('body,html').animate({
        scrollTop: scrollTopY
      },{
        duration: self.options.duration,
        easing: self.options.easing,
        queue: self.options.queue,
        complete: function() {
          if(self.options.complete != null) {
            self.options.complete($(selfElement));
          }
        }
      });
      return false;
    });
  },
  this.init();
}