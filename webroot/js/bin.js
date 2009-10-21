var li3Bin = {
	
	setup: function() {
		$('#clean').hide();
		this.setupColor();
		this.setupContrast();
		this.setupCodeSizers();
		this.setupCopy();
	},
	
	setupColor: function(options) {
		$('#toggle-color').click(function() {
			$('#clean, #paste').animate({
				opacity : 'toggle'
			},'fast');
		});
	},
	
	setupContrast: function() {
		$('#toggle-contrast').click(function() {
			if ($('#clean, #paste').hasClass('dark')) {
				$('#clean, #paste').removeClass('dark');
			} else {
				$('#clean, #paste').addClass('dark');
			}
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
                li3.clearCli();
                $('#cli-display').html('Copied to clipboard!');
                $('#cli-display').animate({
					height: 'show',
					opacity: 'show'
				});
				setTimeout(function() {
					$('#cli-display').animate({
						height: 'hide',
						opacity: 'hide'
					});
				}, 2000);
            });

		}
	}
}
