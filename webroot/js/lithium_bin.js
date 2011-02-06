/*global ZeroClipboard */
var LithiumBin = {
	_config: {
		assetBase: '',
	},

	setup: function(options) {
		this._config = options;
		this.setupColor();
		this.setupCodeSizers();
		this.setupToggleContentMode();
		this.loadJs();
		this.wrap();
	},

	loadJs: function() {
		$.getScript(this._config.assetBase + "/js/ZeroClipboard/ZeroClipboard.js", function() {
			LithiumBin.setupCopy();
		});
	},

	setupColor: function(options) {
		$('#toggle-color').click(function() {
			$('#clean, #paste').animate({
				opacity : 'toggle'
			},'fast');
		});
	},

	setupCodeSizers: function() {
		$('#code-smaller').click(function() {
			var currentFontSize = parseFloat($('.code:first').css('font-size'), 10);
			$('.code').css('font-size', currentFontSize-1);
		});
		$('#code-bigger').click(function() {
			var currentFontSize = parseFloat($('.code:first').css('font-size'), 10);
			$('.code').css('font-size', currentFontSize+1);
		});
	},

	setupCopy: function() {
		if ($('#copy-to-clipboard')) {
			ZeroClipboard.setMoviePath(this._config.assetBase + "/js/ZeroClipboard/ZeroClipboard.swf");
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

	setupToggleContentMode: function() {
		var toogle = false;
		$('.paste .raw').hide();

		$('#toggle-content').click(function() {
			if (toogle) {
				$('.paste .raw').hide();
				$('.paste .parsed').show();
				$(this).text('raw');
			} else {
				$('.paste .raw').show();
				$('.paste .parsed').hide();
				$(this).text('highlight');
			}
			toogle = !toogle;
			return false;
		});
	},

	wrap: function() {
		$('#textwrap').toggle(function() {
			$('pre').addClass('wrap');
		}, function() {
			$('pre').removeClass('wrap');
		});
	}
};
