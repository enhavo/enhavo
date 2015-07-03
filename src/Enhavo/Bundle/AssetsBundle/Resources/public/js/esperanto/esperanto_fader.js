function esperanto_fader(containerselector,options) {
  this.container = containerselector;
  //this.slidescontainer = containerselector.find('[data-slide-container]');
  this.options = options || {speed: 1000, duration: 5000, onswitch: null};
  this.currentIndex = null;
  this.slides = $();
  this.navigationDisabled = false;
  this.sliderInterval = null;

  this.disableNavigation = function () {
    this.navigationDisabled = true;
  },
    this.enableNavigation = function () {
      this.navigationDisabled = false;
    },
    this.initNavigation = function () {
      var self = this;
      this.container.find('[data-slider-switch]').on('click', function () {
        if (self.navigationDisabled) return;
        var newIndex = self.container.find('[data-slider-switch]').index($(this));
        if (newIndex != self.currentIndex) {
          self.gotoIndex(newIndex);
        }
      });
    },
    this.gotoIndex = function (index) {
      var self = this;
      this.disableNavigation();
      clearInterval(this.sliderInterval);
      if (this.currentIndex != null) {
        this.slides.eq(this.currentIndex).fadeOut(this.options.speed);
      }

      this.container.find('[data-slider-switch]').removeClass('active');
      this.container.find('[data-slider-switch]').eq(index).addClass('active');

      this.slides.eq(index).fadeIn(this.options.speed, function () {
        self.currentIndex = index;
        self.enableNavigation();
      });
      this.handleSlideInterval();
    },
    this.handleSlideInterval = function() {
      var self = this;
      this.sliderInterval = setInterval(function () {
        var nextIndex = self.currentIndex + 1;
        if(self.slides.eq(nextIndex).length == 0) {
          nextIndex = 0;
        }
        self.gotoIndex(nextIndex);
      }, this.options.duration);
    },
    this.handleSlideMouseover = function () {
      var self = this;
      this.slides.on('mouseover', function () {
        clearInterval(self.sliderInterval);
      }).on('mouseleave', function () {
        self.handleSlideInterval();
      });
    },
    this.displayInitSlide = function () {
      this.gotoIndex(0);
    },
    this.init = function () {
      this.slides = this.container.find('[data-slider-slide]');
      this.displayInitSlide();
      this.initNavigation();
      this.handleSlideMouseover();
      this.enableNavigation();
    },
    this.init();
};