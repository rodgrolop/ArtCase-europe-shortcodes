(function($) {
	
// Hover effect for megamenu shortcode	

$('.acs-link-element').hover(
    function () {
    	var image_url = $(this).data('tn');
        $('#acs-img-holder').attr('src', image_url);
    }
);

// Close modal on background click

$('.cart-modal').on('click', function(event) {
	if (event.target.id === 'cart-modal') {
		$('.close-modal').click();
	}
});

// Preselect Device 

function getCookie () {
	var name = 'solidMoodSelectedDevice=';
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
	    var c = ca[i];
	    while (c.charAt(0) == ' ') {
	      c = c.substring(1);
	    }
	    if (c.indexOf(name) == 0) {
	      return c.substring(name.length, c.length);
	    }
	}
	return 'iphone-11-pro-max';
}

$('#custom-select-device').find('option[value=' + getCookie() + ']').attr('selected','selected');

// Select Device and store in session

$('#custom-select-device').on('change', function() {
	var selectedDevice = $(this).children("option:selected").val();
	document.cookie = 'solidMoodSelectedDevice' + '=' + selectedDevice + "; path=/";
	$('.product select.pa_device').find('option[value=' + selectedDevice + ']').attr('selected','selected').trigger('change');
});

})( jQuery );
