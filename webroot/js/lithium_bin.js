var LithiumBin = {
	setup: function() {
		this.setupColor();
		this.setupCodeSizers();
		this.wrap();
	},
	setupColor: function(options) {
		$('.paste .raw').hide();

		$('#toggle-color').click(function() {
			$('.paste .raw, .paste .parsed').toggle();
			return false;
		});
	},

	setupCodeSizers: function() {
		$('#code-smaller').click(function() {
			var currentFontSize = parseFloat($('.paste .parsed:first').css('font-size'), 10);
			$('.paste .parsed').css('font-size', currentFontSize-1);
			return false;
		});
		$('#code-bigger').click(function() {
			var currentFontSize = parseFloat($('.paste .parsed:first').css('font-size'), 10);
			$('.paste .parsed').css('font-size', currentFontSize+1);
			return false;
		});
	},

	wrap: function() {
		$('#textwrap').toggle(function() {
			$('pre').addClass('wrap');
			return false;
		}, function() {
			$('pre').removeClass('wrap');
			return false;
		});
	}
};
