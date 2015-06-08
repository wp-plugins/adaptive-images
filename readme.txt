
=== Adaptive Images for WordPress ===

Contributors: nevma
Donate link: http://www.nevma.gr/
Tags: adaptive images, responsive images, mobile images, resize images, optimize images, adaptive, responsive, mobile, resize, optimize, images
Requires at least: 4.0
Tested up to: 4.2.2
Stable tag: 0.3.51
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adaptive images plugin transparently resizes your images, per device screen size, in order to reduce download times in 
mobile environments. 



== Description ==

= Adaptive Images Solution =

Resizes and optimizes images delivered to mobile devices, so that the total download time is drastically reduced. It
wraps and expands the functionality of Adaptive Images http://adaptive-images.com/ for WordPress! It works 
transparently, as a filter between the device and your website. With a tiny bit of Javascript on the client, it 
determines the device size and sets a special resolution cookie, so that it can serve an appropriate optimized 
image size to it.

= Fundamental goals = 

 1. Reduce the total download time in mobile devices dramatically.
 2. Work unobtrusively. Enable it and it works. Disable it vanishes.
 3. Provide a totally transparent solution, independant of your code.
 4. Be unaware of the yet-not-finalized `picture` element or `srcset` attribute.

= How to test it = 

 1. Test with a tool like Webpagetest http://www.webpagetest.org/. Make sure you set the "Emulate Mobile Browser" 
    setting in the "Advanced Settings" > "Chrome" tab. 
 2. Test with a tool like GTmetrix http://gtmetrix.com/. Make sure you enable mobile device testing. The plugin will 
    have no effect on desktop sized devices.
 3. Check in the `/wp-contents/cache` directory to see the `/adaptive-images` directory and its contents. This is 
    where the resized images are kept and cached by default.
 4. Test with an actual mobile device, a smartphone or tablet. Watch your website load in a snap.

Do not test with a normal desktop browser! A usual browser will simply be served the original images without them 
being resized at all. This is the whole idea: serving each device the image sizes which are appropriate for it.

= Default breakpoints =

 - 1024px wide screens
 - 640px wide screens
 - 480px wide screens

The plugin takes into account each device in its landscape -that is its largest- orientation, because it cannot 
predict which one the user will choose to use or when they might switch between orientations. But the overall result 
remains excellent.

 = Stuff to keep in mind = 

 - The plugin needs to add a little bit of code to your `.htaccess` file in order to function properly. It removes 
   this code  once disabled. If you are not cool with that, then&hellip; tough luck! 
 - It cannot work out of the box with a CDN (or Varnish server, for that matter), because the CDN or Varnish server 
   are unaware of the device size cookie and they cannot know in a definitive way which image they should serve or 
   cache for each device.
 - It does not care whether the device is actually mobile or not. It checks the device screen resolution. If you have 
   set your breakpoints big enough then it should work just as good for desktop devices as well. However it targets 
   mostly the mobile ones.
 - The resized versions of the pictures are kept in a special directory in the `/wp-content/cache` folder. 

= Credits = 

 - The plugin uses Adaptive Images http://adaptive-images.com/ adapted for WordPress. So far this functionality is only
   very slightly changed.
 - It was originally it was based on the WP-Resolutions plugin https://github.com/JorgenHookham/WP-Resolutions/ but 
   now it is a complete rewrite!

Thanks for using the plugin and, please, do let us know how it works (or doesn't work) for you. We love creative feedback!



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

= Can it work with a CDN/Varnish? =

Not out of the box! The reason is that the service responsible for delivering the images (in this case the CDN or Varnish) must be aware of the resizing process and the client cookie it is based on. If one has access to their CDN or
Varnish configuration, then they could probably extend it to take that cookie into account and act accordingly.

= Does the plugin help with art direction? =

No! Simple as that. Art direction in responsive images is an entirely different, yet important, problem. This plugin 
does not tackle with it. But it works in a supplementary way. This means that you can combine it with any art 
direction solution. This plugin will continue to work and serve resized versions of your responsive images even after 
you have done art direction on them.



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

= 0.3.51 =

 - Minor bug in settings page url parameters.
 - Documentation stuff.

= 0.3.5 =

 - Allow for default browser cache settings.
 - More thorough debugging information.
 - Added diagnostics debugging in the settings page.
 - Nicer admin area user messages with icons.
 - Minor fixes here (and there).
 - Documentation enhancements.

= 0.3.04 =

 - Documentation enhancements (yeah).
 - Added &quot;noptimize&quot; tag in HEAD Javascript to exclude it from optimizers.

= 0.3.03 =

 - Added Last-modified HTTP header for resized images, as the best practices do suggest.

= 0.3.02 =

 - When no device size/resolution is detected then show the original image. Helps avoid misunderstandings and sends 
   search engines the actual images instead of the resized ones.

= 0.3.01 =

 - Documentation enhancements.

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
