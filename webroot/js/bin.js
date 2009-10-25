var toogle = false;
$(document).ready(function() {
	$('div#clean').hide();

	$('#placeholder').each(function() {
		var clip = new ZeroClipboard.Client();
		clip.glue($('#placeholder')[0]);
		clip.setText($('#clean').text());
		clip.addEventListener('complete', function(client) {
			alert('Copied text to clipboard!');
		});
	});

	$('a#toggle').click(function() {
		if (toogle) {
			$('div#clean').hide();
			$('div#paste').show();
			$(this).text('clean');
		} else {
			$('div#clean').show();
			$('div#paste').hide();
			$(this).text('highlight');
		}
		toogle = !toogle;
		return false;
	});

});