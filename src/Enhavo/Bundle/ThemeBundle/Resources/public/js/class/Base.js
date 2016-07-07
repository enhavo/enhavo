function Base() {
    var self = this;

    this.init = function() {

      //var pathname = window.location.pathname;
      //if (pathname == "/") {
      //  this.handleArticleFeed();
      //}
      this.mapInit();
      this.handleMenu();
      this.viewOnTablet();
      this.sliceCategories();
      this.initSlider();
      this.getCategoryHeight();
      this.setWaypoints();
    };

  this.initSlider = function () {
    $('.slider').slick({
      dots: true,
      fade: true,
      prevArrow: '<button type="button" class="slick-prev"><i class="icon-chevron-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="icon-chevron-right"></i></button>',
      adaptiveHeight: true
    });
  }

  this.getCategoryHeight = function() {
    var maxHeight = 0;

    $("[data-category-item]:lt(3)").each(function(){
      var thisH = $(this).innerHeight();
      if (thisH > maxHeight) { maxHeight = thisH; }
    });

    $("[data-category-item]:lt(3)").css("min-height", maxHeight);
  }

  this.viewport = function() {
      var e = window, a = 'inner';
      if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
      }
      return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
    };

    this.handleMenu = function(){
      $("[data-open-menu]").on("click", function(event){
        event.preventDefault();
        $("[data-menu-items]").toggleClass("open");
      });
      $("[data-menu-items]").on("click", function(){
        $(this).removeClass("open");
      });
    }

    this.mapInit = function() {
        var mapOptions = {
            zoom: 12,
            scrollwheel: false,
            draggable: false,
            disableDefaultUI: true,
            center: new google.maps.LatLng(48.19898, 11.25217),
            styles: [{"featureType":"all","elementType":"all","stylers":[{"lightness":"29"},{"invert_lightness":true},{"hue":"#008fff"},{"saturation":"-73"}]},{"featureType":"all","elementType":"labels","stylers":[{"saturation":"-72"}]},{"featureType":"administrative","elementType":"all","stylers":[{"lightness":"32"},{"weight":"0.42"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":"-53"},{"saturation":"-66"}]},{"featureType":"landscape","elementType":"all","stylers":[{"lightness":"-86"},{"gamma":"1.13"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"hue":"#006dff"},{"lightness":"4"},{"gamma":"1.44"},{"saturation":"-67"}]},{"featureType":"landscape","elementType":"geometry.stroke","stylers":[{"lightness":"5"}]},{"featureType":"landscape","elementType":"labels.text.fill","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"weight":"0.84"},{"gamma":"0.5"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"visibility":"off"},{"weight":"0.79"},{"gamma":"0.5"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"simplified"},{"lightness":"-78"},{"saturation":"-91"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"color":"#ffffff"},{"lightness":"-69"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"lightness":"5"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"lightness":"10"},{"gamma":"1"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"lightness":"10"},{"saturation":"-100"}]},{"featureType":"transit","elementType":"all","stylers":[{"lightness":"-35"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"saturation":"-97"},{"lightness":"-14"}]}]
        };
        var mapElement = document.getElementById('map');
        if(mapElement) {
            var map = new google.maps.Map(mapElement, mapOptions);
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(48.19898, 11.25217),
                map: map,
                title: 'Acme Magazine, 82256 Fürstenfeldbruck',
                icon:  '/bundles/enhavotheme/img/map-marker.png'
            });
        }
    };

  this.handleArticleFeed = function() {
    var clickCount = 2;
    var article = $("[data-article-item]");
    var numberOfArticles = $("[data-article-item]").length;

    article.slice(clickCount).hide();

    $("[data-more-articles]").on("click", function (event) {
      event.preventDefault();
      if (clickCount <= numberOfArticles) {
        article.show();
        clickCount++
        article.slice(clickCount).hide();
      }
      if (clickCount >= numberOfArticles) {
        $(this).html("Show less articles <br> <i class='icon-circle-with-minus'></i>");
      }

      if (clickCount > numberOfArticles) {
        clickCount = 2;
        article.slice(clickCount).fadeOut();
        $('html, body').animate({ scrollTop: ($("#article-feed").offset().top)}, 'slow');
        $(this).html("Load more articles <br> <i class='icon-circle-with-plus'></i>");
      }
    });
  }

  this.sliceCategories = function() {
    var category = $("[data-category-item]");
    category.slice(3).hide();
  }

  this.setWaypoints = function() {
    new Waypoint({
      element: $('#article-feed').get(0),
      handler: function () {
        $(".mobile-app").addClass("animate");
      },
      offset: "40%"
    });
    new Waypoint({
      element: $('#image-text').get(0),
      handler: function () {
        $("#image-text .image").addClass("animate");
      },
      offset: "70%"
    });
  };


  this.viewOnTablet = function(){
    $("[data-tablet-btn]").on("click", function(){
      document.getElementById('tablet').contentWindow.location.reload();
      $("#ipad-overlay").fadeIn();
      $("main, header, footer").addClass("blur");
      $("body").addClass("fixed");
    })
    $("[data-close-tabletview]").on("click", function(){
      $("#ipad-overlay").fadeOut();
      $("main, header, footer").removeClass("blur");
      $("body").removeClass("fixed");
    });
  }

}