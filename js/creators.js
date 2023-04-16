jQuery(function($){
    
    // window height, scroll
    var windowHeight = $(window).height();
    var scrollToTopVisible = false;
    var scrollToTopButton = $('#scroll-to-top');
    var windowScroll = function(){
        if($(window).scrollTop() + windowHeight > $(document).height() - 10
                && windowHeight < $(document).height() - 100) {
            if (!scrollToTopVisible) {
                scrollToTopButton.removeClass('scroll-to-top-hidden');
                scrollToTopVisible = true;
            }
        } else {
            if (scrollToTopVisible) {
                scrollToTopButton.addClass('scroll-to-top-hidden');
                scrollToTopVisible = false;
            }
        }
    };
    $(window).resize(function() {
        windowHeight = $(window).height();
        
        windowScroll();
    });
    $(window).scroll(windowScroll);
    // scroll to top click
    scrollToTopButton.on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });
    
    
    // scripts on pages
    switch(document.body.id) {
        case 'page-site-index':
            $('#home-read-more, .slide-down-icon').on('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                
                var homeDesc = $('.home-description');
                var homeDescPos = homeDesc.offset().top;
                var homeDescHeight = homeDesc.height();
                var scrollTo = homeDescPos - ((windowHeight - homeDescHeight) / 2);
                $('html, body').animate({ scrollTop: scrollTo });
            });
            
            var slideDownIconObject = $('.slide-down-icon');
            var slideDownIconVisible = false;
            var textObjectArray = [
                $('.home-description-left p'), 
                $('.home-firmbook-description p')
            ];
            $(window).load(function() { // on images loaded
                // auto show slide-down-icon
                setTimeout(function(){
                    slideDownIconObject.removeClass('slide-down-icon-hidden');
                    slideDownIconVisible = true;
                }, 2000);
                
                var checkSlidingText = function(){
                    // slide left/right descriptions
                    var scrollTarget = $(window).scrollTop() + (windowHeight * 0.62);
                    $(textObjectArray).each(function(){
                        if ($(this).offset().top < scrollTarget) {
                            this.removeClass('hidden-slide-from-left hidden-slide-from-right');
                            var index = textObjectArray.indexOf(this);
                            textObjectArray.splice(index, 1);
                        }
                    });
                };
                checkSlidingText();
                
                $(window).scroll(function () {
                    // show slide-down-icon on scroll
                    if (!slideDownIconVisible) {
                        slideDownIconObject.removeClass('slide-down-icon-hidden');
                        slideDownIconVisible = true;
                    }
                    
                    checkSlidingText();
                });
                
            });
            break;
    }
    
    // iframe detection
    if (window!=window.top) {
        window.top.location.href = window.location.href;
    }
    
});
