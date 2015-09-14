// Get screen dimensions for landscape orientation.

var screen_width = Math.max( screen.width, screen.height );

// Get screen device pixel ratio.

var devicePixelRatio = window.devicePixelRatio ? window.devicePixelRatio : 1;

// Set Adaptive Images Wordpress plugin cookie.

document.cookie = 'resolution=' + screen_width + ',' + devicePixelRatio + '; path=/';