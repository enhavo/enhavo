/**
 * Created by jenniferhelbing on 17.11.14.
 */

var page = new Page();
var sectionMinHeight = 700;

$(function() {
  page.init();
  var fullscreen = new esperanto_fullscreen($('[data-fullscreen]'));
  new esperanto_scrollto($('#li-home,#li-top'),{duration:1200,easing:'easeOutExpo'});
  new esperanto_scrollto($('#li-appointments,#li-download,#li-concept,#li-about_us'),{spacing_top:139,duration:1200,easing:'easeOutExpo'});

  setSectionHeight();
  handleMenu();
  $(window).on('resize',function() {
    setSectionHeight();
  });
});

function handleMenu() {
  var menu = $("nav");
  // All list items
  var menuItems = menu.find("ul li");
  // Anchors corresponding to menu items
  var scrollItems = menuItems.map(function(){
    var item = $($(this).data("scrollto"));
    if (item.length) { return item; }
  });

  $(window).on('scroll',function(){
    var fromTop = $(this).scrollTop();
    var cur = scrollItems.map(function(){
      if ($(this).offset().top < fromTop+$(window).height()/2)
        return this;
    });
    menuItems.removeClass("active");
    if(cur.length) {
      cur = cur[cur.length-1];
      var anchor = cur && cur.length ? $(cur).attr("id") : "";
      // Set/remove active class
      menuItems.filter("[data-scrollto=#"+anchor+"]").addClass("active");
    }
  });
}

function setSectionHeight() {
  $('section').each(function() {
    var newHeight = $(window).height();
    if(this.id == 'home') {
      newHeight -= $('header').height();
    }
    newHeight -= $('footer').height();
    if(newHeight < sectionMinHeight) {
      newHeight = sectionMinHeight;
    }
    $(this).css("min-height",newHeight);
  });
}

function Page() {

    var self = this;
    var current_img_concept = 1;
    var current_img_about_us = 1;

    this.init = function() {

    self.render();

    self.initPageSize();

    self.initFooter();

    self.initHomeArrows();

    self.initAppointment();

    self.initDownload();

    self.initSlider('#concept');

    self.initSlider('#about_us');

    self.initSliderRightClick('#concept');

    self.initSliderLeftClick('#concept')

    self.initSliderLeftClick('#about_us');

    self.initSliderRightClick('#about_us');

    self.initSliderAuto('#concept');

    self.initSliderAuto('#about_us');

    self.initResizeListener();

    self.initContentSmallSize('#concept');

    self.initContentSmallSize('#about_us');

    $("#arrow_send").click(function() {
      var data = $("#emailForm").serialize();
      $.post("/email",data,function(response) {
        alert('Nachricht gesendet!');
        $("#emailForm").get(0).reset();
      });
      return false;
    });

    $("#participate").click(function() {
      data = $("#appointmentForm").serialize();
      $.post("/appointmentMail",data,function(response) {
        alert('Nachricht gesendet!');
        $("#appointmentForm").get(0).reset();
      });
      return false;
    });

    $("#arrow_right_tick").click(function() {
      data = $("#appointmentForm").serialize();
      $.post("/appointmentMail",data,function(response) {
      });
      return false;
    });

    $('#maps').click(function() {
      self.initMap();
    })
    }

  this.initResizeListener = function() {
    $(window).on("resize",function() {
      self.render();
    });
  };

  this.render = function() {

    var current_window_width = $(window).width();
    //Sliderbreite anpassen
    var current_slider_width_concept = current_window_width*($('#concept .slider').children().length);
    $('#concept .slider').css({"width": current_slider_width_concept+"px", "right": (current_img_concept-1)*current_window_width+"px"});

    var current_slider_width_about_us = current_window_width*$('#about_us .slider').children().length;
    $('#about_us .slider').css({"width": current_slider_width_about_us+"px", "right": (current_img_about_us-1)*current_window_width+"px"});

    //Bilderbreite anpassen
    $('.concept-img').css({"width": current_window_width+"px"});

    $('.about_us-img').css({"width": current_window_width+"px"});

  }

  this.initPageSize = function() {
    if($('#concept .content-small .text').height() > 230) {
      $('#concept #read_more').show();
      self.initReadMoreText();
    }

    if($('#about_us .content-small .text').height() > 230) {
      //$('#about_us .content-small .text').html(html + " <div class='more'> weiterlesen</div>")
      $('#about_us #read_more').show();
      self.initReadMoreText();
    }
  }

  this.initContentSmallSize = function(selector) {
    var content_height = 180;
    content_height += $(selector).find('[data-heading]').height();
    content_height += $(selector).find('[data-underline]').height();
    content_height += $(selector).find('[data-d2]').height();
    $(selector).find('[data-content-small]').css({"height": content_height+"px"});
  }

  this.initReadMoreText = function() {
    $('#about_us #read_more').click(function() {
      var height = $('#about_us .text').height();
      var height30 = height + 30 + $('#about_us .heading').height() + 40 + $('#about_us .underline').height() + 70;
      $('#about_us #d2').css({"height": height +"px"});
      $('#about_us .content-small').css({"height": height30 +"px"});

      $('#about_us #read_more').hide();
      $('#about_us #close').show();
      self.initClose();
    })

    $('#concept #read_more').click(function() {
      var height = $('#concept .text').height();
      var height30 = height + 30 + $('#concept .heading').height() + 40 + $('#concept .underline').height() + 70;
      $('#concept #d2').css({"height": height +"px"});
      $('#concept .content-small').css({"height": height30 +"px"});

      $('#concept #read_more').hide();
      $('#concept #close').show();
      self.initClose();
    })
  }

  this.initReadMore = function(item, nextItem) {
    $('#appointments '+ item +' #read_more').click(function() {
      $('#appointments '+ item +' #text').removeAttr("style");
      $('#appointments '+ item +' #read_more').hide();
      if(nextItem != null) {
        var top = 38 + $('#appointments '+ item +' #text').height();
        $('#appointments '+ nextItem).css({"margin-top": top +"px"});
        var big_height = $('#appointments .content-big').height() + $('#appointments '+ item +' #text').height() -60;
        $('#appointments .content-big').css({"height": big_height +"px"});
      }
    })
  }

  this.initClose = function() {
    $('#about_us #close').click(function() {
      $('#about_us #d2').css({"height": "222px"});
      $('#about_us .content-small').css({"height": "500px"});
      $('#about_us #read_more').show();
      $('#about_us #close').hide();
    })

    $('#concept #close').click(function() {
      $('#concept #d2').css({"height": "222px"});
      $('#concept .content-small').css({"height": "500px"});
      $('#concept #read_more').show();
      $('#concept #close').hide();
    })

  }

  this.initFooter = function() {
    var margin_footer = 1;

    $('#contact_content').css({"height": $('#maps_mail').innerHeight()});
    $('#contact_text').css({"margin-top": -$('#contact_text').height()/2});
    $('#mail').trigger("click");

    $(window).bind('scroll', function(){
      if ($(document).height()-$(window).height() <= $(window).scrollTop()) {
        $("#footer").animate({"bottom": "24px"}, 100);
        if(overlay == true) {
          $(".overlay").animate({"bottom": "96px"}, 100);
        }
        margin_footer = 24;
      }
      else {
        if(margin_footer != 0) {
          $("#footer").animate({"bottom": "0px"}, 100);
          if(overlay == true) {
            $(".overlay").animate({"bottom": "72px"}, 100);
          }
          margin_footer = 0;
        }
      }
    });


    var window_max_height = $(window).height()-90;
    var overlay = false;

    $('#about-text').click(function() {
      $('.overlay').css({"max-height": ($(window).height() - 100)});
      if(overlay == false) {
        $('#about').css({"display": "block"});
        $('#contact').css({"display": "none"});
        $('footer').css({"z-index": "220"});
        if ($(document).height()-$(window).height() <= $(window).scrollTop()) {
          $('#about').animate({"bottom": "96px"}, {
            duration: 1000,
            complete: function() {
              $('#arrow-footer').hide();
              $('#arrow_close').show();
            }
          });
        } else {
          $('#about').animate({"bottom": "72px"}, {
            duration: 1000,
            complete: function() {
              $('#arrow-footer').hide();
              $('#arrow_close').show();
            }
          });
        }
        if($('#about_content').height() > ($(window).height()-90)) {
          $('.overlay').css({"overflow-y": "scroll"});
        }
        else {
          $('.overlay').css({"overflow-y": "hidden"});
        }
        overlay = true;
      }
    })

    $('#arrow-footer').click(function() {
      if(overlay == false) {
        $('#contact').css({"display": "block"});
        if ($(document).height()-$(window).height() <= $(window).scrollTop()) {
          $('.overlay').css({"overflow-y": "hidden"});
          $('#about').css({"display": "none"});
          $('#contact').animate({"bottom": "96px"}, {
            duration: 1000,
            complete: function() {
              $('#mail').trigger("click");
              $('#arrow-footer').hide();
              $('#arrow_close').show();
            }
          });
        }
        else {
          $('.overlay').css({"overflow-y": "hidden"});
          $('#about').css({"display": "none"});
          $('#contact').animate({"bottom": "72px"}, {
            duration: 1000,
            complete: function() {
              $('#mail').trigger("click");
              $('#arrow-footer').hide();
              $('#arrow_close').show();
            }
          });
        }
        overlay = true;
      }
    })

    $('#arrow_close').click(function() {
      $('.overlay').animate({"bottom": "-1500"}, {
        duration: 1000,
        complete: function() {
          $('#arrow-footer').show();
          $('#arrow_close').hide();
          $('.overlay').css({"display": "none"});
        }
      });
      $('footer').css({"z-index": "210"});
      overlay = false;
    })

    $('#about_close').click(function() {
      $('.overlay').animate({"bottom": "-1500"}, {
        duration: 1000,
        complete: function() {
          $('#arrow-footer').show();
          $('#arrow_close').hide();
          $('.overlay').css({"display": "none"});
        }
      });
      $('footer').css({"z-index": "210"});
      overlay = false;
    })

    $('#maps').click(function() {
      $('#maps').css({"background": "#b83617", "color": "white"});
      $('#mail').css({"background": "#f0f0f0", "color": "#4c5d66"});
      $('#mapContainer').show();
      $('#mail_formular').hide();
    })

    $('#mail').click(function() {
      $('#mail').css({"background": "#b83617", "color": "white"});
      $('#maps').css({"background": "#f0f0f0", "color": "#4c5d66"});
      $('#mail_formular').show();
      $('#mapContainer').hide();
    })

  }

  this.initHomeArrows = function() {

    var overflow = $('#text-content').height() > $('#text-home').height();

    self.initHomeArrowDownClick();
    self.initHomeArrowUpClick();

    if(overflow) {
      $('#home-arrow-down').show();
      $('#home-arrow-up').hide();
    } else {
      $('#home-arrow-down').hide();
      $('#home-arrow-up').hide();
    }
  }

  var pos_home_text = 0;
  this.initHomeArrowUpClick = function() {

    $('#home-arrow-up').click(function() {
      if(pos_home_text < 0) {
        $('#text-content').animate({"top": "+=74px"},{duration:"fast",complete: function() {
          $('#home-arrow-up').show();
          if(pos_home_text >= 0) {
            $('#home-arrow-up').hide();
          }
        }});
        pos_home_text = pos_home_text + 74;
        $('#home-arrow-down').show();
      } else {
        $('#home-arrow-up').hide();
      }

    })

  }


  this.initHomeArrowDownClick = function() {
    var scroll_height = $('#text-home').height() - $('#text-content').height();
    $('#home-arrow-down').click(function() {
      if(pos_home_text > scroll_height) {
        $('#text-content').animate({
          "top": "-=74px"
        }, {
          duration: "fast",
          complete: function() {
            $('#home-arrow-up').show();
            if(pos_home_text <= scroll_height) {
              $('#home-arrow-down').hide();
            }
          }
        });
        pos_home_text = pos_home_text - 74;

      } else {
        $('#home-arrow-down').hide();
      }
    })

  }

  this.initAppointment = function() {

    var items = document.getElementsByName("item");
    var content_height = 149+30+98+98;
    for(var i = 1; i < items.length+1; i++) {
      var item = "#i" + i;
      if($('#appointments '+ item +' #text').height() > 60) {
        $('#appointments '+ item +' #text').css({"height": "60px"});
        $('#appointments '+ item +' #read_more').show();
        var nextItem;
        if(i+1 <= items.length) {
          var next = i+1;
          nextItem =  "#i" + next;
        } else {
          nextItem = null;
        }
        self.initReadMore(item, nextItem);
      }
    }
    content_height += $('#appointment-text').height();

    $('#appointments .content-big').css({"height": content_height + "px"});

    $('.pen-img').click(function() {
      var content_height2 = 149+30+98+98+29+35+192+29;
      var top = $('#appointments').offset();
      $(window).scrollTop(top.top-110)
      var current_id = this.getAttribute("id");
      $('#appointment-text').hide();
      $('#appointment_content').show();
      $('#appointment_back').show();
      $('#appointment_content .text').removeAttr("style");
      var big_height = $('#appointments .content-big').height() + ($('#appointment_content .text').height() - 100);
      $('#appointments .content-big').css({"height": big_height +"px"});
      $('#appointment_content .item').attr("id", current_id);
      var day = $('#appointment-text #'+current_id+' .day').html();
      $('#appointment_content .date .day').html(day);
      var month = $('#appointment-text #'+current_id+' .month').html();
      $('#appointment_content .date .month').html(month);
      var date = day + " " + month;
      $('#input_date').val(date);
      var title = $('#appointment-text #'+current_id+' .title').html();
      $('#appointment_content .title').html(title);
      $('#input_title').val(title);
      var text = $('#appointment-text #'+current_id+' .text').html();
      $('#appointment_content .text').html(text);
      var time = $('#appointment-text #'+current_id+' .date').attr("time");
      $('#appointment_content .time').html(time);
      content_height2 += $('#appointment_content .text').height();
      $('#appointments .content-big').css({"height": content_height2 + "px"});
    })

    $('#appointment_back').click(function() {
      var content_height = 149+30+98+98;
      var top = $('#appointments').offset();
      $(window).scrollTop(top.top-110)
      $('#appointment_content').hide();
      $('#appointment_back').hide();
      $('#appointment-text').show();
      $('#appointments .content-big').css({"height": "790px"});
      content_height += $('#appointment-text').height();

      $('#appointments .content-big').css({"height": content_height + "px"});
    })
  }

  this.initDownload = function() {
    var content_height_download = 151+30+30+30+96;
    content_height_download += $('#download-text').height();
    $('#download .content-big').css({"height": content_height_download + "px"});
  }

  this.initMap = function() {
    var latlng = new google.maps.LatLng(48.220750,11.069561);
    var swStyle = [
      {
          "featureType": "landscape",
          "elementType": "geometry",
          "stylers":
            [
              { "hue": "#33ff00" },
              { "color": "#F0F0F0" }
            ]
      },{
        "featureType": "road",
        "elementType": "geometry",
        "stylers":
          [
            { "hue": "#33ff00" },
            { "color": "#868479" }
          ]
      }
    ];
    var myOptions = {
      zoom: 10,
      center: latlng,
      disableDefaultUI: true,
      zoomControl: false,
      panControl: false,
      mapTypeControl: false,
      scaleControl: false,
      streetViewControl: false,
      overviewMapControl: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      styles:swStyle
    };
    var map = new google.maps.Map(document.getElementById("mapContainer"),myOptions);




      /*

       var infoMarker = new google.maps.Marker({
       position: latLngInfo
       });

       infoMarker.setMap(map);

       latLngInfo = new google.maps.LatLng(48.220869,11.069716);

       var markerImage = {
       url: templateUrl+"/images/maps-marker-obermaier.png?2",
       size: new google.maps.Size(231,111),
       origin: new google.maps.Point(0,0),
       anchor: new google.maps.Point(0, 111),
       scaledSize: new google.maps.Size(231,111)
       };

       var infoMarker = new google.maps.Marker({
       position: latLngInfo,
       icon: markerImage
       });

       infoMarker.setMap(map);*/
  }

  var sliderConcept;
  var sliderAboutUs;
  this.initSlider = function(selector) {
    var image_counter = $(selector).find('[data-img]').siblings().length;
    var image_width = $(window).width();
    var slider_width = image_counter*image_width;
    $(selector).find('[data-img]').css({"width": image_width+"px"})
    $(selector).find('[data-slider]').css({"width": slider_width+"px"});
    if(image_counter > 1) {
      $(selector).find('[data-arrow-left]').css({"display": "none"});
      $(selector).find('[data-arrow-right]').css({"display": "block"});
    } else {
      $(selector).find('[data-arrow-left]').css({"display": "none"});
      $(selector).find('[data-arrow-right]').css({"display": "none"});
    }
  }

  this.initSliderAuto = function(selector) {
    if(selector == "#concept") {
      sliderConcept = setInterval(function() {
        if($(selector).find('[data-arrow-right]').css("display") == "block") {
          $(selector).find('[data-arrow-right]').trigger("click");
        }
      }, 8000);
    } else {
      sliderAboutUs = setInterval(function() {
        if($(selector).find('[data-arrow-right]').css("display") == "block") {
          $(selector).find('[data-arrow-right]').trigger("click");
        }
      }, 8000);
    }
  }

  this.initSliderRightClick = function(selector) {

    var image_counter = $(selector).find('[data-img]').siblings().length;
    $(selector).find('[data-arrow-right]').click(function() {

      if(selector == "#concept") {
        clearInterval(sliderConcept);
        self.initSliderAuto("#concept");
      } else {
        clearInterval(sliderAboutUs);
        self.initSliderAuto("#about_us");
      }

      var current_img;
      if(selector == '#concept') {
        current_img = current_img_concept;
      } else {
        current_img = current_img_about_us;
      }
      $(selector).find('[data-arrow-left]').css({"display": "block"});
      var current_window_width = $(window).width();
      $(selector).find('[data-slider]').animate({"right": "+="+current_window_width+"px"});
      if(current_img +1 < image_counter){
        current_img = current_img +1;
      } else {
        $(selector).find('[data-arrow-right]').css({"display": "none"});
        current_img = current_img +1;
      }
      if(selector == '#concept') {
        current_img_concept = current_img;
      } else {
        current_img_about_us = current_img;
      }
    })
  }

  this.initSliderLeftClick = function(selector) {

    var image_counter = $(selector).find('[data-img]').siblings().length;
    $(selector).find('[data-arrow-left]').click(function() {
      if(selector == "#concept") {
        clearInterval(sliderConcept);
        self.initSliderAuto("#concept");
      } else {
        clearInterval(sliderAboutUs);
        self.initSliderAuto("#about_us");
      }
      var current_img;
      if(selector == '#concept') {
        current_img = current_img_concept;
      } else {
        current_img = current_img_about_us;
      }
      $(selector).find('[data-arrow-right]').css({"display": "block"});
      var current_window_width = $(window).width();
      $(selector).find('[data-slider]').animate({"right": "-="+current_window_width+"px"});
      if(current_img-1 > 1){
        current_img = current_img -1;
      } else {
        $(selector).find('[data-arrow-left]').css({"display": "none"});
        current_img = current_img -1;
      }
      if(selector == '#concept') {
        current_img_concept = current_img;
      } else {
        current_img_about_us = current_img;
      }
    })
  }
}
