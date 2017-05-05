
function Base() {
    var self = this;

    this.init = function() {

      var pathname = window.location.pathname;
      if (pathname == "/en/index" || pathname == "/de/index") {
        this.handleArticleFeed();
        this.setWaypoints();
      }
      this.initMap();
      this.handleMenu();
      this.viewOnTablet();
      this.initSlider();
      this.getCategoryHeight();
      this.handleFormMessage();
      this.createStickyElements();
      this.fadeOutSidebar();
      this.setTitleHeightOfThreePictureItem();
      this.toggleDropDown();
      this.sortArticleStream();
    };

    this.sortArticleStream = function() {
      $('[data-category]').on('click', function(event) {

        var currentCategory = $(this).text();
        $("[data-current-category]").html(currentCategory);

        event.preventDefault();
        var categoryID = $(this).attr('data-category');
        $.ajax({
          url : '/article-stream/category/'+categoryID,
          success: function(articles) {
            $('[data-article-stream]').html($.parseHTML(articles));
          },
        })

      });
    }

    this.toggleDropDown = function() {
      $("[data-toggle-dropdown]").on('click', function() {
        $(this).find($("[data-dropdown]")).toggle();
      });
    }

  this.fadeOutSidebar = function() {
    var documentHeight = $(document).height();
    var contentHeight = $("main").height();

    if ( documentHeight > contentHeight ) {
      $(".fade-out-widgets").show();
    }
  }

  this.createStickyElements = function() {
    var stickyNavTop = $("header").height();

    var stickyNav = function(){
      var scrollTop = $(window).scrollTop();

      if (scrollTop > stickyNavTop) {
        $('.widget-area').addClass('sticky');
      } else {
        $('.widget-area').removeClass('sticky');
      }
    };
    stickyNav();
    $(window).scroll(function() {
      stickyNav();
    });
  }

  this.handleFormMessage = function(){
    $("[data-send-message]").on("click", function(){
      $("#contact_message").addClass("show");
    })
  }

  this.setTitleHeightOfThreePictureItem = function(){
    var maxHeight = -1;

    $('[data-image-title]').each(function() {
      maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
    });

    $('[data-image-title]').each(function() {
      $(this).height(maxHeight);
    });
  }

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

    this.initMap = function() {
      var mapOptions = {
        zoom: 12,
        scrollwheel: false,
        draggable: false,
        disableDefaultUI: true,
        center: new google.maps.LatLng(48.19898, 11.25217),
        styles: [
          {
            "featureType": "all",
            "elementType": "all",
            "stylers": [
              {
                "hue": "#ff0000"
              },
              {
                "saturation": -100
              },
              {
                "lightness": -30
              }
            ]
          },
          {
            "featureType": "all",
            "elementType": "labels.text.fill",
            "stylers": [
              {
                "color": "#ffffff"
              }
            ]
          },
          {
            "featureType": "all",
            "elementType": "labels.text.stroke",
            "stylers": [
              {
                "color": "#353535"
              }
            ]
          },
          {
            "featureType": "landscape",
            "elementType": "geometry",
            "stylers": [
              {
                "color": "#656565"
              }
            ]
          },
          {
            "featureType": "landscape.man_made",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "visibility": "on"
              },
              {
                "color": "#464646"
              }
            ]
          },
          {
            "featureType": "landscape.natural",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#464646"
              }
            ]
          },
          {
            "featureType": "poi",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#505050"
              }
            ]
          },
          {
            "featureType": "poi",
            "elementType": "geometry.stroke",
            "stylers": [
              {
                "color": "#808080"
              }
            ]
          },
          {
            "featureType": "poi.sports_complex",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#363535"
              }
            ]
          },
          {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [
              {
                "color": "#454545"
              }
            ]
          },
          {
            "featureType": "road.highway",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#363535"
              }
            ]
          },
          {
            "featureType": "road.highway.controlled_access",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#363535"
              }
            ]
          },
          {
            "featureType": "road.arterial",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#363535"
              }
            ]
          },
          {
            "featureType": "road.local",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#363535"
              }
            ]
          },
          {
            "featureType": "transit.line",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#363535"
              }
            ]
          },
          {
            "featureType": "transit.station.rail",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#363535"
              }
            ]
          },
          {
            "featureType": "water",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#363535"
              }
            ]
          }
        ]
      };
      var mapElement = document.getElementById('map');
      if(mapElement) {
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(48.19898, 11.25217),
          map: map,
          title: 'Acme Magazine, 82256 Fürstenfeldbruck',
          icon:  '/bundles/enhavoproject/img/map-marker.png'
        });
      }
    };

  this.handleArticleFeed = function() {
    var clickCount = 5;
    var article = $("[data-article-item]");
    var numberOfArticles = $("[data-article-item]").length;
    article.slice(clickCount).hide();

    $("[data-more-articles]").on("click", function (event) {
      event.preventDefault();
      if (clickCount < numberOfArticles) {
        clickCount = numberOfArticles;
        article.show();
        article.slice(clickCount).hide();
        $(this).html("Show less articles <br> <i class='icon-circle-with-minus'></i>");
      } else {
        clickCount = 5;
        article.slice(clickCount).fadeOut();
        $('html, body').animate({ scrollTop: ($("#article-feed").offset().top)}, 'slow');
        $(this).html("Show more articles <br> <i class='icon-circle-with-plus'></i>");
      }
    });

    // hide elements if articles less than 5
    if ($("[data-article-item]").length <= 5) {
      $(".load-articles").hide();
    }

    // hide elements if articles = 0
    if ($("[data-article-item]").length == 0 ) {
      $(".load-articles").hide();
      $("[data-article-section-subline]").html("Sorry, No Articles yet...")
      $(".mobile-app").css("top", "300px");
    }

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
      $("[data-tablet-view]").attr("src", window.location.pathname);
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