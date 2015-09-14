// Get screen dimensions for portrait orientation.

var screen_width = screen.width;

// Get screen device pixel ratio.

var devicePixelRatio = window.devicePixelRatio ? window.devicePixelRatio : 1;

// Set Adaptive Images Wordpress plugin cookie.

document.cookie = 'resolution=' + screen_width + ',' + devicePixelRatio + '; path=/';