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