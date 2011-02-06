/*global ZeroClipboard */
var LithiumBin = {
	_config: {
		assetBase: '',
	},

	setup: function(options) {
		this._config = options;
		this.setupColor();
		this.setupCodeSizers();
		this.loadJs();
		this.wrap();
	},

	loadJs: function() {
		$.getScript(this._config.assetBase + "/js/ZeroClipboard.js", function() {
			LithiumBin.setupCopy();
		});
	},

	setupColor: function(options) {
		$('.paste .raw').hide();

		$('#toggle-color').click(function() {
			$('.paste .raw, .paste .parsed').animate({
				opacity : 'toggle'
			},'fast');
		});
	},

	setupCodeSizers: function() {
		$('#code-smaller').click(function() {
			var currentFontSize = parseFloat($('.paste .parsed:first').css('font-size'), 10);
			$('.paste .parsed').css('font-size', currentFontSize-1);
		});
		$('#code-bigger').click(function() {
			var currentFontSize = parseFloat($('.paste .parsed:first').css('font-size'), 10);
			$('.paste .parsed').css('font-size', currentFontSize+1);
		});
	},

	setupCopy: function() {
		if ($('#copy-to-clipboard').length) {
			ZeroClipboard.setMoviePath(this._config.assetBase + "/js/ZeroClipboard.swf");
			var clip = new ZeroClipboard.Client();

			$('#copy-to-clipboard').click(function(e) {
				e.preventDefault();
			});
			$('#copy-to-clipboard').wrap(
				'<div id="copy-to-clipboard-container" style="position: relative;" />'
			);
			clip.glue('copy-to-clipboard', 'copy-to-clipboard-container');
			clip.setText($('.paste .raw').text());
			clip.addEventListener('complete', function(client, text) {
                $('#content').prepend('<div id="copied-notification">...copied!</div>');
                $('#copied-notification').animate({
					height: 'show',
					opacity: 'show'
				});
				setTimeout(function() {
					$('#copied-notification').animate({
						height: 'hide',
						opacity: 'hide'
					});
				}, 2000);
            });
		}
	},

	wrap: function() {
		$('#textwrap').toggle(function() {
			$('pre').addClass('wrap');
		}, function() {
			$('pre').removeClass('wrap');
		});
	}
};
