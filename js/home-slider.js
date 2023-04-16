jQuery(function($) {
	$('.home-slider').each(function(){
		$(this).find('a, button').on('click', function(e) {
			var $this = $(this);
			var href = $this.attr('href');
			if (typeof href != 'undefined' && href[0] != '#' && href != '') return;
			var forward = href == '#back' ? false : true;

			e.preventDefault();

			$slider = $this.closest('.home-slider');
			var currentSlide = $slider.attr('data-current-slide');

			$slider.find('> div.hide-left').removeClass('hide-left');	// cleanup
			$slider.find('> div.hide-right').removeClass('hide-right');	// cleanup

			if (forward) {
				var nextSlide = (currentSlide % $slider.attr('data-slides')) + 1;
				setTimeout(function() {
					$slider.find('> div').removeClass('backwards');	// slide from right
					$slider.find('> div:nth-child('+currentSlide+')').removeClass('visible').addClass('hide-left');
					$slider.find('> div:nth-child('+nextSlide+')').addClass('visible');
					$slider.attr('data-current-slide', nextSlide);
				}, 0);
			} else {
				var prevSlide = (currentSlide-2 % $slider.attr('data-slides')) + 1;
				if (prevSlide < 1) prevSlide += $slider.attr('data-slides');
				$slider.find('> div').addClass('backwards');	// slide from left
				setTimeout(function() {
					$slider.find('> div:nth-child('+currentSlide+')').removeClass('visible').addClass('hide-right');
					$slider.find('> div:nth-child('+prevSlide+')').addClass('visible');
					$slider.attr('data-current-slide', prevSlide);
				}, 0);
			}
		});
	});
});
