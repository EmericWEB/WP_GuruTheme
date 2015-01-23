(function($, document, window) {
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
    
    var app = {
        init:function() {
             $(".owl").owlCarousel({

                autoPlay: 3000, //Set AutoPlay to 3 seconds

                items : 4,
                itemsDesktop : [1199,3],
                itemsDesktopSmall : [979,3]

                });
        }
    };
    
    app.init();
    
    var slider = {
        interval : false,
        zIndex : 0,
        n : 0,
        ratio : 1920 / 1280,
        current : 0,
        loaded : false,
        $slide : $('div.slider > div.slide'),
        init : function() {
            this.n = this.$slide.length;
            var src = '';
            /*
            this.$slide.each(function() {
                src = $(this).find('img:first').prop('src');
                $(this).css({backgroundImage : 'url(' + src + ')'});
                $(this).find('img:first').hide();
            });
            this.$slide.find('img').remove();
            */
           if(this.n > 2)   {
               this.current = 1;
                this.start();
                
                $('#slider-next').click(function() {
                   slider.next(); 
                   return false;
                });
                
                $('#slider-prev').click(function() {
                   slider.prev();
                   return false;
                });
                
            }
            else {
                $('.slider-nav').hide();
            }
        },
        start: function() {
            this.interval = setInterval(function() {
                slider.go(slider.current);
                
            }, 3000);
        },
        stop : function() {
            clearInterval(this.interval);
        },
        go : function(idx) {
            //alert('slide ' + idx)
            //alert('slide ' + slider.current)
                slider.current = idx;
            this.$slide.eq(idx).css({zIndex : slider.zIndex++})
                .stop(true, true).hide().fadeOut(0).fadeIn(1200, '', function() {
                //slider.go(slider.current);
            });
            //return (idx + 1) % slider.n;
        },
        anim : function() {
            
        },
        prev : function() {
            this.stop();
            this.go( (slider.current -1) % slider.n);
        },
        next : function() {
            this.stop();
            this.go( (slider.current + 1) % slider.n);
        }
    };
    
    slider.init();

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

    if(scroll)$('html, body').stop(true, true).animate( {scrollTop : top}, 400)
    
    // don't block any other keys
    return !scroll;
});
    
}(jQuery, document, window ));