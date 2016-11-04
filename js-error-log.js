var throttle = 0;

window.onerror = function(msg, url, line, col)
{
	// Return if we've sent more than 10 errors.
	throttle++;
	if (throttle > 10) return;

	// Log the error.
	var req = new XMLHttpRequest();
	var params = 'action=js_log_error&msg=' + encodeURIComponent(msg) + '&url=' + encodeURIComponent(url) + "&line=" + line;
	req.open( 'POST', ajaxurl );
	req.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
	req.send(params);
};
