function esperanto_slider(containerselector,options) {
  this.container = containerselector;
  this.slidescontainer = containerselector.find('[data-slide-container]');
  this.options = options || {speed:1000,onswitch:null};
  this.currentIndex = 0;
  this.slides = $();
  this.slidewidth = 0;
  this.navigationDisabled = false;

  this.disableNavigation = function() {
    this.navigationDisabled = true;
  },
    this.enableNavigation = function() {
      this.navigationDisabled = false;
    },
    this.initNavigation = function() {
      var self = this;
      this.container.find("[data-slider-next]").on('click',function() {
        if(self.navigationDisabled) return;
        var changedIndex = self.getChangedIndex(1);
        self.gotoIndex(changedIndex);
      });
      this.container.find("[data-slider-prev]").on('click',function() {
        if(self.navigationDisabled) return;
        var changedIndex = self.getChangedIndex(-1);
        self.gotoIndex(changedIndex);
      });
    },
    this.gotoIndex = function(newIndex) {
      if(newIndex == this.currentIndex) return;

      var self = this;
      var nextSlide = this.slides.eq(newIndex);
      var currentSlide = this.slides.eq(this.currentIndex);
      var currentSlideAnimateParam = {opacity:0};

      var slidesMoveIntoDirection = "left";
      if(this.currentIndex > newIndex && !(this.currentIndex == this.slides.length-1 && newIndex == 0) || this.currentIndex == 0 && newIndex == this.slides.length-1) {
        slidesMoveIntoDirection = "right";
      }

      if(slidesMoveIntoDirection == "left") {
        currentSlideAnimateParam.left = -this.slidewidth;
      } else {
        currentSlideAnimateParam.left = this.slidewidth;
      }

      currentSlide.stop().animate(currentSlideAnimateParam,{duration:this.options.speed});
      self.disableNavigation();

      nextSlide.stop().animate({left:0,opacity:1},{duration:this.options.speed,complete:function() {

        var maybeNextSlideIndex = self.getChangedIndex(newIndex-self.currentIndex+1);
        var maybePrevSlideIndex = self.getChangedIndex(newIndex-self.currentIndex-1);
        var maybeNextSlide = self.slides.eq(maybeNextSlideIndex);
        var maybePrevSlide = self.slides.eq(maybePrevSlideIndex);

        if(maybeNextSlideIndex != newIndex) {
          maybeNextSlide.css("left", self.slidewidth);
        }
        if(maybePrevSlideIndex != newIndex) {
          maybePrevSlide.css("left", -self.slidewidth);
        }
        self.currentIndex = newIndex;
        self.enableNavigation();
      }});
    },
    this.setNextIndex = function() {
      this.changeIndex(1);
    },
    this.setPreviousIndex = function() {
      this.changeIndex(-1);
    },
    this.getChangedIndex = function(value) {
      var changedIndex = this.currentIndex+value;
      changedIndex = (changedIndex+this.slides.length)%(this.slides.length);
      return changedIndex;
    },
    this.changeIndex = function(value) {
      var nextIndex = this.getChangedIndex(value);
      this.currentIndex = nextIndex;
    },
    this.setDimensions = function() {
      var self = this;
      this.slidewidth = this.slidescontainer.width();
      this.slides.width(this.slidewidth);
      this.slides.each(function(index) {
        $(this).css("left",index*self.slidewidth);
      });
      var maxSlideHeight = 0;
      this.slides.each(function() {
        if($(this).height() > maxSlideHeight) {
          maxSlideHeight = $(this).height();
        }
      });
      this.slides.height(maxSlideHeight);
      this.container.height(maxSlideHeight);
      this.slidescontainer.height(maxSlideHeight);
    },
    this.init = function() {
      this.slides = this.container.find('[data-slide]');
      this.setDimensions();
      this.initNavigation();
    },
    this.init();
}

