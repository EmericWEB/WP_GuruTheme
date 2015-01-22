(function($, document, window, google) {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
    
    
    /* here we go */
    /*
   var scrollfix = false;
    $(window).scroll ( function () {
        if($(this).scrollTop() > 120) {
            if(! scrollfix) {
                scrollfix = true;
                $('header#masthead').addClass('fix').find('#logo').css('display', 'none').stop(true, true).fadeIn(1200);
            }
        }
        else {
            if(scrollfix) scrollfix = false;
            $('header#masthead').removeClass('fix').find('#logo').stop(true, true).show();
        }
        
    })
    /**/
   /*
    $('header#masthead').scrollToFixed({
        preFixed: function() { $(this).addClass('fix');},
        fixed: function() {$(this).find('#logo').css('display', 'none').fadeIn(1200); },
        postFixed: function() { $(this).removeClass('fix'); }
    });
    /**/
var $inside = $('#inside').hide().fadeIn(),
$content = $('#content'),
animspeed = 300,
home_url = $('body').data('home'),
cache = {};


$(window).bind( 'hashchange', function( event ) {
    
    var h = home_url + '/' +  window.location.hash.substr(1);
        $('footer#colophon').hide();
        $inside.stop(true, true).fadeOut(animspeed, function() {
        $inside.load(h + " #main", function() {
            //alert( "Load was performed." );
            load();
            /*imagesLoaded('#content').on('progress',  function( instance, image ) {
            $(image.img).parent().fadeIn(animspeed);
            })*/  

            })
        });
    return  true;
    });
/*
    $('.showMapLink').live('click', function() {
        top.location.hash = "map";
    });
*/
if(window.location.hash) {
    $(window).trigger("hashchange");
}

function load() {

    var $imgLoaded = imagesLoaded('#content');
    $imgLoaded.on('progress',  function( instance, image ) {
        $(image.img).parent().fadeIn(animspeed);
    }).on('always', function() {
        
            $inside.fadeIn(animspeed);
        $('footer#colophon').show();
            equalizer();
            mozaik.size();
            captcha.init();
            gmap.init();
            
            $('select.ddslick').each(function(i) {
                var $this = $(this);
                
                $this.chosen({width: "100%"}).change(function() {
                        var h = $this.val() == 0 ? '' : $this.find('option:selected').data('price') + ' &euro;';
                        
                        $this.parents('.prod').find('.price').html(h);
                        
                        if($this.val() != 0) {
                            
                            $('select.ddslick').each(function(j) {
                                if(j!==i) {
                                    $(this).val(0);
                                    $(this).trigger('chosen:updated')
                                    $(this).parents('.prod').find('.price').html('');
                                    //$(this).selectric('refresh');
                                }
                            });
                        }
                    });
                /*
                $this.selectric({
                    onChange : function(element) {
                        var h = !$(element).val() ? ''
                        : $this.find('option:selected').data('price') + ' &euro;';
                        
                        $this.parents('.prod').find('.price').html(h);
                        
                        if($(element).val() != 0) {
                            
                            $('select.ddslick').each(function(j) {
                                if(j!==i) {
                                    $(this).val(0);
                                    $(this).parents('.prod').find('.price').html('');
                                    $(this).selectric('refresh');
                                }
                            });
                        }
                    }
                });
                */
                //slicker.push($this);
            });
            return true;
            
    });
}
    load();

$('#menu-top a').addClass('gajax');
$('#footer-navigation a').addClass('gajax');

$content.on('mouseenter', 'div.rollop', function() {
    $('div.rollop').not(this).addClass('op')
    //$(this).removeClass('op')
});

$content.on('mouseleave', 'div.rollop', function(event) {
    $(this).addClass('op')
    $('div.rollop').removeClass('op')
});
/*
var hash = {
    tag : function() {
        
$('a.gajax').each(function() {
    // just do it one time for each link
    if(! $(this).data('hash')) {
        var h = this.href;
        //window.location.hash = h.substr(home_url.length + 1);
        this.href= home_url + h.substr(home_url.length + 1);
        $(this).data('hash', true);
    }
    
})
    }
}

hash.tag();
*/
$('#page').on('click', 'a.gajax', function() {
    var h = this.href;
    window.location.hash = h.substr(home_url.length + 1);
    $(this).data('hash');
    return false;
})

var resMenu = {
    width : 768,
    wrapper : '',
    menu : '',
    button : '',
    isRes : false,
    toggle : true,
    init : function (selector) {
      this.wrapper = $(selector);
      this.button = this.wrapper.find('.navbar-button');
      this.menu = this.wrapper.find('.navbar').parent();
      $('button.navbar-toggle').click(function(e) {
          e.preventDefault();
          if(resMenu.toggle) {
              resMenu.toggle = false;
              if($(this).hasClass('open')) {
                  $(this).removeClass('open');
                    resMenu.menu.animate(true, true).slideUp(animspeed, function() {resMenu.toggle = true});
              }
              else {
                  $(this).addClass('open');
                    resMenu.menu.animate(true, true).slideDown(animspeed, function() {resMenu.toggle = true});
              }
          }
          //resMenu.menu.animate(true, true).slideToggle(900);
      })
      $(window).resize(function () {
          resMenu.doit();
      });
      this.doit();
    },
    doit : function() {
        if( $(window).width() >= this.width) {
            if(this.isRes) {
                this.isRes = false;
                this.button.hide();
                $('button.navbar-toggle').removeClass('open');
                this.menu.removeClass('smart').show();
            }
        }
        else {
            if(! this.isRes) {
                this.isRes = true;
                this.button.show();
                this.menu.addClass('smart').hide();
            }
        }
    }
}

resMenu.init('nav#primary-navigation');

var fixHeader = {
    fix : false,
    init : function() {
        $(window).scroll(function () {
            if($(window).scrollTop() > 69) {
                if(! fixHeader.fix) {
                    fixHeader.fix = true;
                    $('.header-main').addClass('fix').hide().fadeIn(animspeed/2);
                    //$('#logo').hide().fadeIn(animspeed);
                }
            }
            else if(fixHeader.fix) {
                    fixHeader.fix = false;
                    $('.header-main').removeClass('fix');
                
            }
        });
    }
};
fixHeader.init();

var captcha = {
    init : function() {
        $('<input type="checkbox" name="guru_diduagree" value="1" />').hide().appendTo('form');
        $('form').append('<input type="hidden" name="guru_captcha" value="1" />');
    }
};
//captcha.init();

var equalizer = function(){
    //return;
    $('.alacarte > .clearfix').each(function() {
        
        //$(this).find('> div').height($(this).height());
        h = 'auto';
        var top = 0,
        max = 0;
        $(this).find('> div').each(function(i) {
            
            if($(this).height() > max) {
                max = $(this).height();
            }
            
            if(i === 0) { top = $(this).offset().top; }
            else if ($(this).offset().top !== top) {
                top = -1;
            }
        });
        
        $(this).find('> div').height( top !== -1 ? max : 'auto');
        
    });
    return ;
        var $w = $('.wines');
        if($w.length) {
            var h = $w.parents('.row').first('div').height();
            //alert(h)
            if(h > $w.height()) {
                $w.innerHeight(h);
            }
        }
    };

$(window).resize(function () {
    equalizer();
});
//equalizer();


var mozaik = {
    init : function() {
        if($('.col-xs-6  .mozaik').length) {
        }
            this.size();
            $(window).resize(mozaik.size);
    },
    size : function() {
        //alert('size')
        var h = $('.col-xs-6  .mozaik').eq(1).height();
        //alert(h)
        $('.mozaik').parents('a').height(h).css({'overflow' : 'hidden', 'display' : 'block'});
        
        var e = $('.container').width() / 3 ;
        
        $('.page-mozaik .col-eq .mozaik').each(function() {
            $(this).height(e)
        });
        
    }
};
mozaik.init();

var gmap = {
    $canvas : null,
    location : null,
    loaded : false,
    geocoder : null,
    html : '',
    init : function() {
        this.$canvas = $('#map-canvas');
        if(!this.$canvas.length) {
            return;
        }
        this.html = this.$canvas.html();
        if(!this.loaded) {
            this.geocoder = new google.maps.Geocoder();
            this.geocode();
        }
        else {
            this.draw();
        }
    },
    geocode : function() {
        
        var addr = this.$canvas.data('map');
        
        this.geocoder.geocode( { 'address': addr}, function(results, status) {
            //detectBrowser();
          if (status == google.maps.GeocoderStatus.OK) {
              
        // 48.8655920 	2.3504374
                this.loaded = true;
                //var loc = new google.maps.LatLng(48.8656167, 2.3504482),
                gmap.location = results[0].geometry.location;
                gmap.draw();
                
            } else {
              alert("Geocode "+ addr + " was not successful for the following reason: " + status);
            }
          });

    },
    draw : function() {
        

        google.maps.visualRefresh = true;
        var jsonStyle = 
        [
          /*{
            "stylers": [
              { "lightness": -33 },
              { "saturation": -100 },
              { "gamma": 0.22 }
            ]
          },*/{
            "featureType": "road",
            "stylers": [
              { "lightness": -2 }
            ]
          },{
            "featureType": "transit.station",
            "elementType": "labels.text.fill",
            "stylers": [
              { "lightness": 89 }
            ]
          },{
            "featureType": "transit.station",
            "elementType": "labels.icon",
            "stylers": [
              { "gamma": 9.99 }
            ]
          }/*,{
          },{
            "elementType": "labels.text.fill",
            "stylers": [
              { "lightness": 48 },
              { "gamma": 1 }
            ]
          }*/,{
            "featureType": "poi",
            "stylers": [
              { "visibility": "off" }
            ]
          }
        ];
        var mapOptions = {
          //center: new google.maps.LatLng(48.8674905, 2.3466233),
          //center: new google.maps.LatLng(48.8674905, 2.3466233),
          center: gmap.location,
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          styles : jsonStyle
        },
        bounds = new google.maps.LatLngBounds(),

        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions),
        marker = new google.maps.Marker({
            map: map, 
            position: gmap.location
            });
            
        var contentString = this.html;    
        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
    });
        google.maps.event.addDomListener(window, 'resize', function() {
            map.setCenter(gmap.location);
        });
        //infowindow.open(map,marker);
            /*
        bounds.extend(this.location);
        map.fitBounds(bounds);
            */
        google.maps.event.addListenerOnce(map, 'idle', function() {
                google.maps.event.trigger(map, 'resize');
            });
            
            
        google.maps.event.addListener(map, 'click', function() {
            
            
            
            });

        
    }
};
gmap.init();

// little trick for a better scroll XP
var scrollpos = parseInt($(window).height());
$('body').keydown(function (event) { 

    var top = $(window).scrollTop(),
    scroll = false;
    if(event.keyCode == 40) { //down key
        
        top += scrollpos;
        scroll = ! scroll;
    
    } else if (event.keyCode == 38) { //up key
        top -= scrollpos;
        scroll = ! scroll;
    }

    if(scroll)$('html, body').stop(true, true).animate( {scrollTop : top}, animspeed)
    
    // don't block any other keys
    return !scroll;
});
    
}(jQuery, document, window, google));