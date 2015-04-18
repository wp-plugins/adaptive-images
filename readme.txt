=== Adaptive Images for WordPress ===

Contributors: nevma
Donate link: http://www.nevma.gr/
Tags: wurfl, wit, cdn, device detection, mobile, images
Requires at least: 4.0
Tested up to: 4.1.1
Stable tag: 0.2.01
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adaptive images http://adaptive-images.com/ resizes your images, according to each user's screen size, to reduce total 
download time of web pages in mobile devices. 



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

= The plugin has the following fundamental goals = 

 1. Help reduce the total download time of web pages in mobile devices.
 2. Work unobtrusively. You enable it and it works. You disable it and it gets out of your way. Has got to be dead simple.
 3. Provide a transparent solution that is independant of the development process itself.



== Todos ==

This is an off-hand todo list:

 - Check that requested files are strictly images
 - Check that requested files are strictly inside the watched directories.
 - Put configuration in a separate file, which will be included.
 - Clean image cache methods.
 - Clean image cache on demand.



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

1. Screenshot 1 description.



== Upgrade Notice ==

No worries upgrading. Just do it! 



== Changelog ==

= 0.2.01 =

 - The first stable version after the initial fork.
 - Corrected basic PHP errors.
 - Corrected basic WordPress errors.
 - Now compatible with version 4.1.1.

= 0.1 =

 - The version forked from https://github.com/JorgenHookham/WP-Resolutions.
 - It does not work with WordPress version (at least) 4.1.1.
