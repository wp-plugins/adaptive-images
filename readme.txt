
=== Adaptive Images for WordPress ===

Contributors: nevma
Donate link: http://www.nevma.gr/
Tags: adaptive images, responsive images, mobile images, mobile, images, resize, optimize, downsize, wurfl, wit
Requires at least: 4.0
Tested up to: 4.2.2
Stable tag: 0.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adaptive images resizes your images, by device screen size, to reduce download time in mobile devices automatically, 
based on http://adaptive-images.com/. 



== Description ==

= Adaptive Images Solution =

Adaptive images http://adaptive-images.com/ is a standalone solution for resizing and optimizing images which are
delivered to mobile devices so that the total download time is drastically reduced. It uses a bit of Javascript on the
client, to determine the device size and set a special cookie, and PHP on the server to serve the images. 

This plugin wraps this functionality especially for WordPress! It was based on a fork of the WP-Resolutions plugin
https://github.com/JorgenHookham/WP-Resolutions, which was never added to the WordPress plugin repository. It has been
completely rewritten and made compatible with the current versions of WordPress.

The resized versions of the pictures are kept in a special directory in the `/wp-content/cache` folder. The plugin does
not work like a CDN. It is a self hosted, in-your-website solution. And it works!

By default the following breakpoints are in effect: 

 - `1024px screens`
 - ` 640px screens`
 - ` 480px screens`

Bear in mind that we need to take into account each device in its portait orientation, which is gives us the biggest
width. But you can change these at will. 

= The plugin has the following fundamental goals = 

 1. Help reduce the total download time of web pages in mobile devices.
 2. Work unobtrusively. You enable it and it works. You disable it and it gets out of your way.
 3. Provide a transparent solution that is independant of the development process itself.
 4. Be unaware of the yet-not-finalized `picture` element or `srcset` attribute.

= How to really test the plugin = 

In order to test if the plugin works you can:

 1. Check in the `/wp-contents/cache` directory to see the `/adaptive-images` directory and its contents. This is 
    where the resized images are kept and cached by default.
 2. Test with a tool like http://www.webpagetest.org/, but first make sure you set the "Emulate Mobile Browser" 
    setting in the "Advanced Settings" > "Chrome" tab. Test with and without this setting and watch the numbers.
 3. Test with a mobile device (a smpartphone or a tablet), and watch your website load in a snap.

Do not test with a normal desktop browser! A usual browser will simply be served the original images without them  
being resized at all. This is the whole idea: serving each device the images sizes which are proper for it.

 = Caution = 

 - The plugin needs to add a little bit of code to your htaccess file in order to function properly. It removes this 
   code  once disabled. If you are not cool with that, then&hellip; tough luck!
 - The plugin cannot work out of the box with a CDN (or Varnish server for that matter), because the CDN or Varnish 
   would not know in a definitive way which image they should cache or serve each time.

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

First came the Adaptive images solution http://adaptive-images.com/ which is still there and works on its own. Then 
came the WP-Resolutions plugin https://github.com/JorgenHookham/WP-Resolutions. But it is not in the WordPress plugin
repository anymore and the Github version is not compatible with the latest WordPress versions. So we are updating and
maintaining it. Many under the hood changes have taken place, but the overall functionality is the same.

= Is this plugin heavy? =

Well, not much really. The image resizing process is not computationally negligible, but the images are only resized 
when they are first requested and then they are cached. However, the images in the watched directories (the ones the 
plugin is responsible for resizing) are ultimately delivered by a PHP script and not a generic Apache process. 

= Can it work with a CDN? =

Not out of the box. The reason is that the service responsible for delivering the images (in this case the CDN) must be
aware of the resizing process and the client cookie it is based on. If one has access to their CDN configuration then 
they could probably extend it to take that cookie into account and act accordingly.

= Does the plugin help with art direction? =

No! Simple as that. Art direction in responsive images is entirely different, yet important, problem. This plugin does
tackle it. But it works in a supplementary way. This means that you can combine it with any art direction solution.
This plugin will continue to work and serve resized versions of the "art-directed" responsive images.



== Screenshots ==

1. Plugin settings page in the admin area.
2. Resized versions of your images are cached by default in `/wp-content/cache/adaptive-images`.
3. Total web page load time is reduced dramatically on a mobile device (tested in http://webpagetest.org/).
4. Each device is served an image resized its real dimensions, therefore a lot smaller in total size.



== Upgrade Notice ==

= 0.3.0 =

Upgrading to version 0.3.0 you may need to:

 - Save settings fresh. If you do not then the plugin will operate with its current default settings without problems
   as it is expected.
 - Manually delete the old image cache directory `/wp-content/cache-ai`. The new default image cache directory is
   `/wp-content/cache/adaptive-images`.

Apologies for the inconvenience! We are still in early versions. What is important is that the plugin actually works 
as intended. We try to minimize the hassle between these versions. This is not expected to happen pretty often.



== Changelog ==

= 0.3.0 =

 - Almost a complete rewrite of the code.
 - Completely updated the settings page to be user friendly.
 - Added action in the settings page for cache cleanup.
 - Added action in the settings page for debug info.
 - Added action in the settings page for cache size calculation.
 - Added watched directories field in the settings page anew.
 - Divided the plugin files into logical parts.
 - Default resolutions changed to 1024, 640 and 480 because the cookie is set based on the max value between screen 
   width and height and most screens have a height between 480 and 640px. Tablets are between 640 and 1024px wide/tall.
   The iPad is 1024px tall. A screen with a width higher than 1024px is probably not a mobile screen.
 - Changed default image cache directory in order to place it inside the expected WordPress `/wp-content/cache`
   directory, so now by default it is `/wp-content/cache/adaptive-images`.
 - Added check for the plugin options.
 - Added check for the PHP GD library.
 - Added check for the .htaccess file.
 - Added upgrade from older versions functions.
 - Added upgrade from 0.2.08 to 0.3.0 versions functions.
 - Added unistall script `uninstall.php`.
 - Documentation enhancements (as usual).


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
