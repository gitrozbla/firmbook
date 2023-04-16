/**
 * Skrypt wyświetlający Przewijający się tekst.
 * 
 * @see TextOverflowScroll
 * 
 * @category scripts
 * @package other
 * @author BAI
 * @copyright (C) 2014 BAI
 */
(function($) {
    
    $.fn.textOverflowScroll = function(options) {
        
        var settings = $.extend( {
          'delay'   :   5000,
          'time'    :   5000,
          'direction':  'vertical',
          'animationType': 'swing',
        }, options);
        
        switch (settings.direction) {

            case 'vertical':
                // NOT IMPLEMENTED!

            case 'horizontal':
            default:
                
                return this.each(function(){
                    var object = $(this);
                    //if (object.width() != object[0].scrollWidth) {
                    // auto-roll
                    var distance = object[0].scrollWidth - object.width();
                    object.addClass('text-overflow-scroll')
                        .wrapInner('<div class="text-overflow-scroll-wrap"></div>');
                    var innerObject = object.find('.text-overflow-scroll-wrap');
                    innerObject.data('autoScrollVertical', {
                        time: settings.time, 
                        distance: distance,
                        forward: true
                    });
                    switch (settings.animationType) {
                        case 'swing':
                            setTimeout(function(){
                                autoRollVerticalStart(innerObject);
                            }, settings.delay);
                            break;

                        case 'carousel':
                            object.addClass('text-overflow-scroll-carousel');
                            setTimeout(function(){
                                autoRollVerticalCarouselStart(innerObject, false);
                                /*innerObject.hover(
                                    function() {
                                    	innerObject.stop();
                                    }, 
                                    function() {
                                    	autoRollVerticalCarouselStart(innerObject);
                                    	
                                    }
                                );*/
                            }, settings.delay);
                            break;
                    }
                });
        }
        
        
        function autoRollVerticalStart(object)
        {
            var data = object.data('autoScrollVertical');
            var parentObject = object.parent();
            data.distance = parentObject[0].scrollWidth - parentObject.width();
            
            if (data.forward) {
                var left = -(data.distance);
            } else {
                var left = 0;
            }
            var newForward = !data.forward;
            data.forward = newForward;
            
            object.animate({'left': left}, (data.time)/4, function(){
                setTimeout(function(){
                    autoRollVerticalStart(object);
                }, data.time);
            });
        }
        
        function autoRollVerticalCarouselStart(object, started)
        {
            var data = object.data('autoScrollVertical');
            var parentObject = object.parent();
            
            object.css({'left': parentObject.width()});
            data.distance = parentObject[0].scrollWidth - parentObject.width();
            
            if(!started)
                object.hover(
                	function() {
                		object.stop();
                	}, 
                	function() {
                		object.animate({'left': -(data.distance)}, data.time, 'linear', function(){
                			autoRollVerticalCarouselStart(object, true);
                        });
                	}
                );
            
            object.animate({'left': -(data.distance)}, data.time, 'linear', function(){
                autoRollVerticalCarouselStart(object, true);
            });
            
            
        }
        
        function autoRollVerticalCarouselContinue(object)
        {
            var data = object.data('autoScrollVertical');
            var parentObject = object.parent();
            
            object.css({'left': parentObject.width()});
            data.distance = parentObject[0].scrollWidth - parentObject.width();
            
            object.animate({'left': -(data.distance)}, data.time, 'linear', function(){
                autoRollVerticalCarouselStart(object);
            });
            
            /*object.hover(
            	function() {
            		object.stop();
            	}, 
            	function() {
            		object.animate({'left': -(data.distance)}, data.time, 'linear', function(){
            			autoRollVerticalCarouselStart(object);
                    });
            	}
            );*/
        }
    };
    
    
})(jQuery);