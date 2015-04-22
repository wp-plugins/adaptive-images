
=== Adaptive Images for WordPress ===

Contributors: nevma
Donate link: http://www.nevma.gr/
Tags: adaptive images, responsive images, mobile images, wurfl, wit, resize, optimize, downsize
Requires at least: 4.0
Tested up to: 4.1.2
Stable tag: 0.2.08
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adaptive images http://adaptive-images.com/ resizes your images, by device screen size, to reduce download time in 
mobile devices. 



== Description ==

= Adaptive Images Solution =

Adaptive images http://adaptive-images.com/ is a solution for resizing, downsizing and optimizing images which are 
delivered to mobile devices so that the total download time is drastically reduced. It uses a bit of Javascript on the 
client, in order to set a special cookie, and PHP on the server. 

This plugin wraps this functionality especially for WordPress. It is a fork of the WP-Resolutions plugin 
https://github.com/JorgenHookham/WP-Resolutions, which was never added to the WordPress plugin repository. It has been
updated and made compatible with the current versions of WordPress.

The resized versions of the pictures are kept in a special directory in the `/wp-content` folder. The plugin does not
work like a CDN. It is a self hosted, in-your-website solution. But it works!

By default these breakpoints are taken into consideration: 

 - 1024px screens
 - &nbsp;600px screens
 - &nbsp;320px screens

But you can change this at will!

= The plugin has the following fundamental goals = 

 1. Help reduce the total download time of web pages in mobile devices.
 2. Work unobtrusively. You enable it and it works. You disable it and it gets out of your way.
 3. Provide a transparent solution that is independant of the development process itself.
 4. Be unaware of the yet-not-finalized `picture` element or `srcset` attribute.

= How to test it = 

In order to test if the plugin works you can:

 1. Check in the /wp-contents directory to see the /cache-ai directory and its contents. This is where the resized 
    images are kept and cached.
 2. Test with a tool like http://www.webpagetest.org/, but first make sure you set the "Emulate Mobile Browser" 
    setting in the "Advanced Settings" > "Chrome" tab.

 Do not test with a normal desktop browser! A usual browser will simply be served the original images without them 
 being resized at all. This is the whole idea: serving each device the images sizes which are proper for it.

 = Caution = 

 - The plugin needs to add a little bit of code to your htaccess file in order to function properly. It removes this 
   code  once disabled. If you are not cool with that, then you should not install it!
 - The plugin cannot work as-is with a CDN or Varnish server, because the CDN or Varnish would not know in a definitive 
   way which image they should cache or serve each time.

 = WURFL Image Tailor =

 Also, checkout WURFL Image Tailor connector for WordPress https://wordpress.org/plugins/wurfl-image-tailor-connector/ 
 which is another promising mobile images approach that works as a service. 



== Installation ==

= No surprises here =

 1. Install the plugin via "Plugins > Add New".
 2. Activate the plugin.
 3. It should simply work!
 4. De-activate the plugin to disable it.
 4. Activate the plugin to enable it.



== Frequently Asked Questions ==

= What's the story? =

First came the Adaptive images solution http://adaptive-images.com/ which is still there and it works on its own. Then 
came the WP-Resolutions plugin https://github.com/JorgenHookham/WP-Resolutions. But it is not in the plugin repository
and it is not compatible with the latest WordPress versions. So we are updating and maintaining it (so far).

= Is it heavy? =

Well, not much. The image resizing process is not computationally negligible, but the images are only resized when
necessary and they are cached.



== Screenshots ==

1. Install and activate the plugin.
2. Resized versions of your images are created and saved in an image cache directory in /wp-content.
3. Total web page load time on a mobile device is reduced dramatically.



== Upgrade Notice ==

No worries upgrading. Just do it! 



== Changelog ==

= 0.2.08 =

 - Added cache size calculation.
 - Added cache clean up methods.
 - Added nonces to admin actions.
 - Documentation enhancements.

= 0.2.06 =

 - Settings are now separate in an ai-user-settings.php file.

= 0.2.05 =

 - If the original requested image width and the device screen size are bigger than maximum available breakpoint, then 
   serve the the original image. 

= 0.2.04 =

 - Refactoring code.

= 0.2.03 =

 - Set the default screen size breakpoints to 1024, 600, 320.

= 0.2.02 =

 - Refactoring code to separate Adaptive Images files from the other plugin files.

= 0.2.01 =

 - The first stable version after the initial fork.
 - Corrected basic PHP errors.
 - Corrected basic WordPress errors.
 - Now compatible with version 4.1.1.
 - New document root takes into account installations in subdirectories.

= 0.1 =

 - The version forked from the WP Resolutions plugin https://github.com/JorgenHookham/WP-Resolutions.
 - This version does not work with WordPress anymore (at least version 4.1.1 and upwards).
