var LithiumBin = {
	_config: {},
	
	setup: function(options) {
		this._config = options;
		if (this._config.text) {
			$('#paste').hide();
			$('#clean').show();
		} else {
			$('#clean').hide();
		}
		this.setupColor();
		this.setupCodeSizers();
		this.loadJs();
	},

	loadJs: function() {
		$.getScript(this._config.assetBase + "/js/libraries/ZeroClipboard/ZeroClipboard.js", function() {
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
			var clip = new ZeroClipboard.Client();
			clip.setText($('#clean').text());
			clip.glue($('#copy-to-clipboard')[0]);
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
	}
}
