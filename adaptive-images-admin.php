<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *                                                                                                                *
     *      PLUGIN SETTINGS PAGE FUNCTIONS                                                                            *
     *      ==============================                                                                            *
     *                                                                                                                *
     *      Nevma (info@nevma.gr)                                                                                     *
     *                                                                                                                *
     *                                                                                                                *
     ******************************************************************************************************************/



    // Exit, if file is accessed directly.

    if ( ! defined( 'ABSPATH' ) ) {

        exit; 

    }



    /**
     * Adds some admin related CSS rules for the plugin.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_css () { 

        if ( ! adaptive_images_plugin_is_settings_screen() ) {

            return;

        } ?>
        
        <link rel = "stylesheet" href = "<?php echo adaptive_images_plugin_get_url(); ?>/css/admin.css" /> <?php

    }



    /**
     * Adds some admin related Javascript for the plugin.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_js () { 

        if ( ! adaptive_images_plugin_is_settings_screen() ) {

            return;

        } ?>

        <script src = "<?php echo adaptive_images_plugin_get_url(); ?>/js/admin.js" type = "text/javascript"></script> <?php

    }



    /**
     * Adds a link to the adaptive images settings in the plugins listing page for convenience.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param array $links The plugin links array in the plugins listing page.
     * 
     * @return void
     */

    function adaptive_images_admin_settings_link_add ( $links ) {
        
        $links[] = '<a href = "options-general.php?page=adaptive-images">' . 'Settings' . '</a>';

        return $links;

    }



    /**
     * Adds a link to the adaptive images settings in the plugins listing page for convenience.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param array $links The plugin links array in the plugins listing page.
     * 
     * @return void
     */

    function adaptive_images_admin_settings_row_meta_add ( $links, $file ) {

        if ( $file != adaptive_images_plugin_get_name() ) {

            return $links;

        }
        
        $links[] = '<a href = "https://wordpress.org/plugins/adaptive-images/">Plugin page</a>';
        $links[] = '<a href = "http://wordpress.org/support/plugin/adaptive-images">Support page</a>';

        return $links;

    }



    /**
     * Adds the plugin settings page to the admin area.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_settings_page_add () {

        // Adds the plugin's options page as a submenu of the admin settings.

        $hook_name = add_options_page( 
            'Adaptive Images',                         // page title
            'Adaptive Images',                         // menu title
            'manage_options',                          // capability
            'adaptive-images',                         // menu slug
            'adaptive_images_admin_print_options_page' // print function callback
        );

        // Adds the action which adds the plugin admin actions.

        add_action( 'admin_head-' . $hook_name, 'adaptive_images_admin_settings_actions' ); 

    }



    /**
     * Registers the plugin settings.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_settings_register () {

        // Registers the plugin settings field.

        register_setting( 
            'adaptive-images-settings',               // option group
            'adaptive-images',                        // option name
            'adaptive_images_admin_settings_sanitize' // function validator callback
        );


        
        // Adds the plugin main settings section.

        add_settings_section(
            'adaptive-images-settings', // id
            '',                         // title
            '',                         // print function callback
            'adaptive-images'           // plugin page
        ); 



        // Adds the adaptive images resolutions field.

        add_settings_field( 
           'resolutions',                                   // id
           'Resolution breakpoints',                        // title 
           'adaptive_images_admin_print_resolutions_field', // print function callback
           'adaptive-images',                               // plugin page
           'adaptive-images-settings'                       // section
        );

        // Adds the adaptive images landscape field.

        add_settings_field( 
           'orientation',                                 // id
           'Landscape orientation',                       // title 
           'adaptive_images_admin_print_landscape_field', // print function callback
           'adaptive-images',                             // plugin page
           'adaptive-images-settings'                     // section
        );

        // Adds the adaptive images hidpi field.

        add_settings_field( 
           'hidpi',                                   // id
           'HiDPI support',                           // title 
           'adaptive_images_admin_print_hidpi_field', // print function callback
           'adaptive-images',                         // plugin page
           'adaptive-images-settings'                 // section
        );

        // Adds the adaptive images CDN support field.

        add_settings_field( 
           'cdn-support',                                   // id
           'CDN support',                                   // title 
           'adaptive_images_admin_print_cdn_support_field', // print function callback
           'adaptive-images',                               // plugin page
           'adaptive-images-settings'                       // section
        );

        // Adds the adaptive images cache directory field.

        add_settings_field( 
           'cache-directory',                                   // id
           'Cache directory',                                   // title 
           'adaptive_images_admin_print_cache_directory_field', // print function callback
           'adaptive-images',                                   // plugin page
           'adaptive-images-settings'                           // section
        );

        // Adds the adaptive images watched directories field.

        add_settings_field( 
           'watched-directories',                                   // id
           'Watched directories',                                   // title 
           'adaptive_images_admin_print_watched_directories_field', // print function callback
           'adaptive-images',                                       // plugin page
           'adaptive-images-settings'                               // section
        );

        // Adds the adaptive images content types field.

        add_settings_field( 
           'content-type',                              // id
           'Image types',                               // title 
           'adaptive_images_admin_print_content_types', // print function callback
           'adaptive-images',                           // plugin page
           'adaptive-images-settings'                   // section
        );

        // Adds the adaptive images JPEG quality field.

        add_settings_field( 
           'jpeg-quality',                                   // id
           'JPEG quality',                                   // title 
           'adaptive_images_admin_print_jpeg_quality_field', // print function callback
           'adaptive-images',                                // plugin page
           'adaptive-images-settings'                        // section
        );

        // Adds the adaptive images sharpen field.

        add_settings_field( 
           'sharpen-images',                                   // id
           'Sharpen images',                                   // title 
           'adaptive_images_admin_print_sharpen_images_field', // print function callback
           'adaptive-images',                                  // plugin page
           'adaptive-images-settings'                          // section
        );

        // Adds the adaptive images watch cache (for stale images) field.

        add_settings_field( 
           'watch-cache',                                   // id
           'Watch cache',                                   // title 
           'adaptive_images_admin_print_watch_cache_field', // print function callback
           'adaptive-images',                               // plugin page
           'adaptive-images-settings'                       // section
        );

        // Adds the adaptive images browser cache duration field.

        add_settings_field( 
           'browser-cache',                                   // id
           'Browser cache',                                   // title 
           'adaptive_images_admin_print_browser_cache_field', // print function callback
           'adaptive-images',                                 // plugin page
           'adaptive-images-settings'                         // section
        );

    }



    /**
     * Prints the resolutions settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_resolutions_field () {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'resolutions' ); ?>

        <input type = "text" id = "adaptive-images[resolutions]" name = "adaptive-images[resolutions]" value = "<?php echo implode( ', ', $options['resolutions'] ); ?>" /> 
        A comma separated list of device widths. <br />
        
        <span class = "adaptive-images-help-short">
            These are the different image sizes that will be created, cached and served to mobile devices.
        </span>

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>Resolution breakpoints</h4>
            <p>
                Think of these numbers as breakpoints or as important device resolutions, that correspond to device widths. When a request is made from any device, the Adaptive Images plugin tries to calculate in which of these breakpoits the device falls into, so that it can serve it an appropriate scaled image size according to them. 
            </p>
            <p>
                <strong>Example 1:</strong>
            </p>
            <p>
                &bull; Device that sends the request: 640px wide <br />
                &bull; Available resolution breakpoints: { 1024, 640, 480 } 
            </p>
            <p>
                =&gt; Bingo, the the 640px breakpoint is chosen.
            </p>
            <p>
                <strong>Example 2:</strong>
            </p>
            <p>
                &bull; Device that sends the request: 800px wide <br />
                &bull; Available resolution breakpoints: { 1024, 640, 480 } 
            </p>
            <p>
                =&gt; The 1024px breakpoint is the closest biggest one.
            </p>
            <p>
                <strong>Example 3:</strong>
            </p>
            <p>
                &bull; Device that sends the request: 320px wide <br />
                &bull; Device pixel density: 2 (HiDPI/retina) <br />
                &bull; Available resolution breakpoints: { 1024, 640, 480 } 
            </p>
            <p>
                =&gt; The 640px breakpoint is chosen because 2x320=640.
            </p>
            <p>
                If the original image size is even smaller than the chosen breakpoint, then the original image is served (because it is too small). If the device is bigger than the biggest breakpoint, then the original image is served again (because it is too big).
            </p>
            <p>
                This way we do not need to create and cache too many image sizes, one for every available device out there. It is up to you to decide how many such resolution breakpoints you need. You may have 100, if you want, or even just 1. However the default 3 are a good default choice, based on the known popular devices of today.
            </p>
        </div> <?php

    }



    /**
     * Prints the landscape settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_landscape_field ()   {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'landscape' ); ?>

        <label for = "adaptive-images[landscape]">
            
            <input type = "checkbox" id = "adaptive-images[landscape]" name = "adaptive-images[landscape]" <?php echo $options['landscape'] ? 'checked = "checked"' : ''; ?> /> 

            Check if plugin should take into account each device&apos;s landscape orientation.

        </label>

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>Landscape orientation</h4>
            <p>
                Users usually hold their devices in the <strong>portrait</strong> orientation like this &#x1f4f1;, but quite often they also hold them in the <strong>landscape</strong> orientation like this <span style = "display: inline-block; transform: rotate(90deg);">&#x1f4f1;</span>, so this setting defines whether the Adaptive Images plugin should or shouldn&apos;t take into account the device orientation when determining its width.
            </p>
        </div> <?php

    }



    /**
     * Prints the hidpi settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_hidpi_field ()   {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'hidpi' ); ?>

        <label for = "adaptive-images[hidpi]">
            
            <input type = "checkbox" id = "adaptive-images[hidpi]" name = "adaptive-images[hidpi]" <?php echo $options['hidpi'] ? 'checked = "checked"' : ''; ?> /> 

            Check if plugin should try to show higher resolution images to HiDPI (retina) screens.
            
            <br />
            
            <span class = "adaptive-images-help-short">
                Provides better image quality for HiDPI (retina) screen devices, but sends them bigger file sizes. 
            </span>


        </label> 

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>HiDPI Support</h4>
            <p>
                Many mobile and tablet screens NOWADAYS have a feature called <strong>HiDPI</strong> (high device pixel density). Most people know these screens as <strong>retina</strong>. What they can do is to take bigger versions of an image and render it to the size instructed by the developer, but with much better clarity (due to the bigger size).
            </p>
            <p>
                For example, if the pixel density of a device is 2, then it can take a 1000px version of a 500px image and show it with much better clarity still seeming 500px. Adaptive Images can detect these devices and serve them bigger images, yet remaining withing the boundaries of the given resolution breakpoints.
            </p>
            <p>
                Of course this results in sending bigger file sizes to these devices, depending on the compression settings you have chosen. Your call.
            </p>
        </div> <?php

    }



    /**
     * Prints the CDN support settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_cdn_support_field ()   {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'cdn-support' ); ?>

        <label for = "adaptive-images[cdn-support]">
            
            <input type = "checkbox" id = "adaptive-images[cdn-support]" name = "adaptive-images[cdn-support]" <?php echo $options['cdn-support'] ? 'checked = "checked"' : ''; ?> /> 

            Check to instruct plugin to cooperate with a CDN, Varnish or other external cache.
            
            <br />
            
            <span class = "adaptive-images-help-short">
                Filters the HTML of your website and adds a special parameter to image urls in order to support CDNs.
            </span>

        </label> 

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>CDN Support</h4>
            <p>
                CDNs, Varnish and other external caching servers, when they are used, stand between your website and your users in order to deliver your content -and you images- to them. With this option the Adaptive Images plugin makes <strong>slight and unobtrusive</strong> changes to your HTML, by adding a special url parameter to your image urls, so that the caching server may know which version of an image to serve each time.
            </p>
            <p>
                ***Uses Javascript on the browser side!***
            </p>
            <p>
                <strong>An image url like:</strong>
            </p>
            <p>
                &bull; <code>http://www.mysite.com/wp-content/uploads/2015/08/summer.jpg</code>
            </p>
            <p>
                <strong>Will become like:</strong>
            </p>
            <p>
                &bull; <code>http://www.mysite.com/wp-content/uploads/2015/08/summer.jpg<strong>?resolution=320,2</strong></code>
            </p>
            <p>
                ***Still experimental feature!***
            </p>
            <p>
                Sorry about that. You are more than welcome to test it, experiment with it and report your feedback to us. We promise to make it stable pretty soon. If you are not sure that you are using a CDN or a caching server, like Varnish, then do not use this feature, it will be of absolutely no use to you.
            </p>
        </div> <?php

    }



    /**
     * Prints the cache directory settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_cache_directory_field () {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'cache-directory' ); ?>

        <input type = "text" id = "adaptive-images[cache-directory]" name = "adaptive-images[cache-directory]" value = "<?php echo $options['cache-directory']; ?>" /> 
        
        Directory inside /wp-content to store the image cache. 

        <br />

        <span class = "adaptive-images-help-short">
            Current path of cache directory on server: 
            <?php 
                $file = trailingslashit( WP_CONTENT_DIR ) . $options['cache-directory'];
                echo $file;
            ?>.
        </span> 

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>Cache directory</h4>
            <p>
                Right now saving the image cache in a directory outside your WordPress installation is not supported. Actually, to be precise, the cache directories must be inside the <code>/wp-content/</code> directory. So, this setting is a relative path inside it. Leaving this setting in its default value, <code>/wp-content/cache/adaptive-images</code>, is the best option because this is what many other plugins use as a standard place for caching stuff.
            </p>
        </div> <?php

    }



    /**
     * Prints the watched directories settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_watched_directories_field () {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'watched-directories' ); ?>

        <textarea id = "adaptive-images[watched-directories]" name = "adaptive-images[watched-directories]" rows = "5"><?php echo implode( "\n", $options['watched-directories'] ); ?></textarea>

        <br />

        <span class = "adaptive-images-help-short">
            Directories to watch for images to resize. Has to be relative paths inside your WordPress installation.
        </span> 

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>Watched directories</h4>
            <p>
                These are the directories in which your original images are saved by WordPress. The Adaptive Images plugin must know which of these directories you want to be watched for resizing its images. The most common are the <code>/wp-content/uploads</code> and the <code>/wp-content/themes</code>. But it could be anything else you choose.
            </p>
            <p>
                Just remember that these directories <strong>must be inside your WordPress installation</strong> in order for the plugin to be able to detect them and serve them in the way it is designed. It is possible for one to keep their &quot;/wp-content/&quot; outside WordPress, but this is case we are still working on. 
            </p>
        </div> <?php

    }



    /**
     * Prints the content types settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_content_types () {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'content-types' ); ?>
        
        <label for = "adaptive-images[content-types-jpeg]">
            
            <input type = "checkbox" id = "adaptive-images[content-types-jpeg]" name = "adaptive-images[content-types-jpeg]" <?php echo in_array( 'jpg', $options['content-types'] ) ? 'checked = "checked"' : ''; ?> /> 

            JPG/JPEG

        </label> 
        
        <label for = "adaptive-images[content-types-png]">
            
            <input type = "checkbox" id = "adaptive-images[content-types-png]" name = "adaptive-images[content-types-png]" <?php echo in_array( 'png', $options['content-types'] ) ? 'checked = "checked"' : ''; ?> /> 

            PNG

        </label> 
        
        <label for = "adaptive-images[content-types-gif]">
            
            <input type = "checkbox" id = "adaptive-images[content-types-gif]" name = "adaptive-images[content-types-gif]" <?php echo in_array( 'gif', $options['content-types'] ) ? 'checked = "checked"' : ''; ?> /> 

            GIF

        </label> 
        
        <br />
        
        <span class = "adaptive-images-help-short">
            Choose the image types that you wish the Adaptive Images plugin to handle, resize and serve.
        </span> 

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>Image types</h4>
            <p>
                The Adaptive Images plugin can handle JPG/JPEG images very well. It also handles PNG images with transparency but not with alpha channel transparency (it actually converts alpha transparency to simple transparency). GIF images are also supported. If you do not choose an image type of the above, then these images will be served in their original version.
            </p>
        </div> <?php

    }



    /**
     * Prints the cache JPEG quality settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_jpeg_quality_field () {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'jpeg-quality' ); ?>

        <select id = "adaptive-images[jpeg-quality]" name = "adaptive-images[jpeg-quality]">
            <?php for ( $quality = 100; $quality >= 5; $quality -= 5 ) : ?> 
                <option value = "<?php echo $quality; ?>" <?php echo $options['jpeg-quality'] == $quality ? 'selected = "selected"' : ''; ?>><?php echo $quality; ?></option>
            <?php endfor; ?>
        </select> 

        Compression level of JPEG images, 100 means best quality but biggest file size. 

        <br />
        
        <span class = "adaptive-images-help-short">
            Usuallly a 75 JPEG compression level works fine for the human eye with very small file sizes. Your call.
        </span> 

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>JPEG quality</h4>
            <p>
                A JPEG image is a compressed image with reduced quality and smaller file size when compared to its original version. However the JPEG algorithm manages to achieve amazing results in reducing the file sizes of images yet retaining excellent quality for the human eye. But JPEG has many compression levels. We measure these levels in a scale of 0-100:
            </p>
            <p>
                &bull; 0 =&gt; low quality, smaller file sizes <br />
                &bull; 100 =&gt; high quality, bigger file sizes <br />
            </p>
            <p>
                It is generally believed that practically speaking a JPEG compression level of around <strong>75</strong> achieves excellent quality for the human eye while keeping the resulting file size orders of magnitude smaller than the original file. Unfortunately, one cannot further compress an already compressed JPEG image, unless they decide to lower its quality.
            </p>
        </div> <?php

    }



    /**
     * Prints the sharpen settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_sharpen_images_field () {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'sharpen-images' ); ?>

        <label for = "adaptive-images[sharpen-images]">
            
            <input type = "checkbox" id = "adaptive-images[sharpen-images]" name = "adaptive-images[sharpen-images]" <?php echo $options['sharpen-images'] ? 'checked = "checked"' : ''; ?> /> 

            Yes, sharpen JPEG images after compressing and resizing, in order to reduce blur.

        </label> 

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>Sharpen images</h4>
            <p>
                When images are resized to smaller dimensions and compressed, eg via the very efficient JPEG algorithm, it is possible that some blur may appear in certain areas. Usually this is not even visible to the human eye. Sharpening the images attempts to reduce this blur in a generic manner, adding a bit of contrast and seeming clarity to them. This is quite safe a process. Leaving it on, in it default value, guarantees a minimum of good quality results.
            </p>
        </div> <?php

    }



    /**
     * Prints the watch cache settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_watch_cache_field () {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'watch-cache' ); ?>

        <label for = "adaptive-images[watch-cache]">
            
            <input type = "checkbox" id = "adaptive-images[watch-cache]" name = "adaptive-images[watch-cache]" <?php echo $options['watch-cache'] ? 'checked = "checked"' : ''; ?> /> 

            Yes, when accessed check if an image has been updated, so as to generate it again.

        </label>

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>Watch cache</h4>
            <p>
                By selecting this feature, when the original version of the image has been updated in the filesystem, the Adaptive Images plugin will detect it the first time the images is accessed again and update the cache accordingly. Usually, this is not much to worry about, unless you edit your images after having uploaded them to your website.
            </p>
        </div> <?php

    }



    /**
     * Prints the browser cache settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_browser_cache_field () {

        $options = adaptive_images_plugin_get_options(); 

        adaptive_images_plugin_check_empty_setting( $options, 'browser-cache' ); ?>

        <select id = "adaptive-images[browser-cache]" name = "adaptive-images[browser-cache]">
            <option value = "0"     <?php echo $options['browser-cache'] == '0'     ? 'selected = "selected"' : ''; ?>> Default </option>
            <option value = "0.125" <?php echo $options['browser-cache'] == '0.125' ? 'selected = "selected"' : ''; ?>> 3  hours </option>
            <option value = "0.25"  <?php echo $options['browser-cache'] == '0.25'  ? 'selected = "selected"' : ''; ?>> 6  hours </option>
            <option value = "0.5"   <?php echo $options['browser-cache'] == '0.5'   ? 'selected = "selected"' : ''; ?>> 12 hours </option>
            <option value = "1"     <?php echo $options['browser-cache'] == '1'     ? 'selected = "selected"' : ''; ?>> 1  day   </option>
            <option value = "7"     <?php echo $options['browser-cache'] == '7'     ? 'selected = "selected"' : ''; ?>> 1  week  </option>
            <option value = "15"    <?php echo $options['browser-cache'] == '15'    ? 'selected = "selected"' : ''; ?>> 2  weeks </option>
            <option value = "30"    <?php echo $options['browser-cache'] == '30'    ? 'selected = "selected"' : ''; ?>> 1  month </option>
            <option value = "60"    <?php echo $options['browser-cache'] == '60'    ? 'selected = "selected"' : ''; ?>> 2  months</option>
            <option value = "90"    <?php echo $options['browser-cache'] == '90'    ? 'selected = "selected"' : ''; ?>> 3  months</option>
            <option value = "180"   <?php echo $options['browser-cache'] == '180'   ? 'selected = "selected"' : ''; ?>> 6  months</option>
            <option value = "365"   <?php echo $options['browser-cache'] == '365'   ? 'selected = "selected"' : ''; ?>> 1  year  </option>
            <option value = "730"   <?php echo $options['browser-cache'] == '730'   ? 'selected = "selected"' : ''; ?>> 2  years </option>
        </select> 

        How long should browsers be instructed to &quot;remember&quot; images. 

        <br />
        
        <span class = "adaptive-images-help-short">
            Unless you have a very special need and you know what you are doing, set this as high as you can.
        </span> 

        <button class = "adaptive-images-help-button"></button> 
        <div class = "adaptive-images-help-content">
            <h4>Browser cache</h4>
            <p>
                Browsers are designed to remember images (and other resources) that they present to their users. This way they do not have to download everything every time a website is accessed and, as a result, websites can load faster. However sometimes some resources do change and a browser needs to be notified about it. This is when a user hits one &quot;Refresh&quot; after the other waiting for fresh content.
            </p>
            <p>
                Fortunately, an uploaded image in WordPress changes very rarely, if ever. So, feel free to <strong>set the browser cache time period as high as you wish</strong>. It will only do good to the download times of your website, especially if you have users who visit it repeatedly.
            </p>
        </div> <?php

    }



    /**
     * Prints the contents of the plugin options page.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_admin_print_options_page () {
    	
        // Add provision for the thickbox dialog window.
        
        add_thickbox(); ?>
                
        <div class = "wrap">

            <?php $checks = adaptive_images_debug_perform_checks(); ?>
            
            <h2>
                Adaptive Images Settings
                <span class = "adaptive-images-info-h2 <?php echo $checks['all'] ? '' : 'error'; ?>">
                    <?php 
                        echo 
                            $checks['all'] ? 
                                '&#10004; Plugin is setup correctly' : 
                                '&#10006; Houston, we have a problem'; 
                    ?>
                </span>
            </h2>

            <div id = "poststuff">

                <div id = "post-body" class = "metabox-holder columns-2">

                    <div id = "postbox-container-1" class = "postbox-container">

                        <div class = "postbox">

                            <h3 class = "hndle">Image cache tools</h3>

                            <div class = "inside">

                                <p>
                                    Cleanup the image cache or simply calculate its current size (takes some time, depending on the cache size).
                                    <span class = "button-wrapper">
                                        <a class = "button-primary" href = "options-general.php?page=adaptive-images&action=calculate-cache-size&_wpnonce=<?php echo wp_create_nonce( 'adaptive-images-calculate-cache-size' ); ?>" title = "Calculate cache size">
                                            Calculate size
                                        </a>
                                        <a class = "button-primary thickbox" href = "#TB_inline?height=300&amp;width=500&amp;inlineId=cleanup-image-cache-modal" title = "Cleanup image cache">
                                            Cleanup cache
                                        </a>
                                    </span>
                                </p>

                            </div> <!-- .inside -->

                        </div> <!-- .postbox -->

                        <!-- Image cache cleanup confirmation dialog. -->

                        <div id = "cleanup-image-cache-modal" style = "display:none;">

                            <h3>Please confirm cache cleanup</h3>
                            <p>
                                Are you sure you want to delete all images in the cache? This means that all cached images will be lost and that they will be created anew, once they are accessed again. This process might take some time.
                            </p>
                            <p style = "text-align: right;">
                                <a class = "button-primary tb-confirm" href = "options-general.php?page=adaptive-images&action=cleanup-image-cache&_wpnonce=<?php echo wp_create_nonce( 'adaptive-images-cleanup-image-cache' ); ?>">
                                    Yes, cleanup cache
                                </a> 
                                <a class = "button-secondary tb-remove" href = "#">
                                    No, leave it be
                                </a> 
                            </p>

                        </div> <!-- #cleanup-image-cache-modal -->

                        <div class = "postbox">

                            <h3 class = "hndle">Debugging Tools</h3>

                            <div class = "inside">

                                <p>
                                    Print plugin useful debug information.
                                    <span class = "button-wrapper">
                                        <a class = "button-primary left" href = "options-general.php?page=adaptive-images&action=print-debug-info&_wpnonce=<?php echo wp_create_nonce( 'adaptive-images-print-debug-info' ); ?>">
                                            Print debug info
                                        </a>
                                        <a class = "button-primary" href = "options-general.php?page=adaptive-images&action=print-diagnostic-info&_wpnonce=<?php echo wp_create_nonce( 'adaptive-images-print-diagnostic-info' ); ?>">
                                            Print diagnostics
                                        </a>
                                    </span>
                                </p>

                            </div>

                        </div> <!-- .postbox -->
                        
                        <div class = "postbox">

                            <h3 class = "hndle">Need some help</h3>

                            <div class = "inside">

                                <p>
                                    Do not hesitate to report any problems you may encounter and send us your suggestions at the <strong><a href = "https://wordpress.org/support/plugin/adaptive-images" target = "_blank">plugin support page</a></strong>.
                                </p>
                                
                            </div> <!-- .inside -->

                            <a id = "adaptive-images-banner" href = "https://wordpress.org/support/plugin/adaptive-images" title = "Need some help" target = "_blank">
                                <img id = "adaptive-images-developer" src = "<?php echo adaptive_images_plugin_get_url(); ?>img/developer.png" alt = "" />
                                <img src = "<?php echo adaptive_images_plugin_get_url(); ?>img/banner.png" alt = "Plugin support page" style = "height: auto; width: 100%; vertical-align: middle;" />
                            </a>

                        </div> <!-- .postbox -->
                        
                        <div class = "postbox">

                            <h3 class = "hndle">Show us your love</h3>

                            <div class = "inside">

                                <p>
                                    &#127775;&#127775;&#127775;&#127775;&#127775;
                                    <br />
                                    We do appreciate honest <strong><a href = "https://wordpress.org/support/view/plugin-reviews/adaptive-images" target = "_blank">reviews and ratings</a></strong>, so feel free to give us one!
                                </p>

                                <?php $options = adaptive_images_plugin_get_options(); ?>
                                
                                <?php global $wp_version; ?>

                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                                    <p>
                                        <input type="hidden" name="cmd" value="_s-xclick">
                                        <input type="hidden" name="hosted_button_id" value="WCES7V9D45HDS">
                                         &#127866;&#127866;&#127866;&#127866;&#127866;
                                        <br />
                                        Should you wish to buy us a glass of beer, then bless you, we prefer weiss! 
                                    </p>
                                    <p>
                                        &#127851;&#127851;&#127851;&#127851;&#127851;
                                        <br />
                                        We are also suckers for fine dark chocolate, so feel free to spoil us with some.
                                    </p>
                                    <p>
                                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style = "margin-top: 5px;" title = "Paypal donation">
                                        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                    </p>
                                </form>
                                
                            </div> <!-- .inside -->

                            <div class = "hndle-reverse">

                                <div class = "inside">

                                    <p>
                                        <a href = "http://www.nevma.gr" target = "_blank" title = "Nevma.Gr">
                                            <img src = "<?php echo adaptive_images_plugin_get_url(); ?>img/nevma.png" alt = "Nevma" style = "height: 20px; width: auto;" />
                                        </a>
                                        <br />
                                        The development team
                                    </p>
                                    
                                </div> <!-- .inside -->
                                
                            </div>

                        </div> <!-- .postbox -->

                    </div> <!-- #postbox-container-1 -->

                    <div id = "post-body-content" style = "position: relative;">

                        <div class = "postbox">

                            <div class = "inside">

                                <form method = "post" action = "options.php">

                                    <p class = "hndle">
                                        <em>If you are not so sure about yourself, just trust the wisely chosen defaults. Hit the question marks for more help.</em>
                                    </p>

                                    <?php 
                                        // Print plugin settings form.

                                        settings_fields( 'adaptive-images-settings' ); 
                                        do_settings_sections( 'adaptive-images' ); 
                                        submit_button( 'Save plugin settings' ); 
                                    ?>

                                    <?php // Override default referer because it might contain other GET data on it. ?>

                                    <input type = "hidden" name = "_wp_http_referer" value = "options-general.php?page=adaptive-images" />

                                </form>

                                <p class = "adaptive-images-version hndle-reverse">Adaptive Images v.<?php echo $options['version']; ?></p>

                            </div> <!-- .inside -->

                        </div> <!-- .postbox -->

                    </div> <!-- #post-body-content -->
                                    
                </div> <!-- post-body -->

            </div> <!-- #poststuff -->

        </div> <!-- .wrap --> <?php

    }



    /**
     * Takes care of the plugin settings page submit actions.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!.
     */

    function adaptive_images_admin_settings_actions () {

        if ( ! isset( $_GET['action'] ) ) {

            return;

        }



        // Cleanup image cache action.

        if ( $_GET['action'] == 'cleanup-image-cache' && 
             wp_verify_nonce( $_GET['_wpnonce'], 'adaptive-images-cleanup-image-cache' ) ) {

            $cache_path = adaptive_images_plugin_get_cache_directory_path();

            $result = adaptive_images_actions_rmdir_recursive( $cache_path ); 

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                'Cleanup image cache <hr />' . 
                '<p>Total files deleted from the adaptive images cache: ' . $result['files'] . '</p>' .  
                '<p>Total directories deleted from the adaptive images cache: ' . $result['dirs'] . '</p>' .  
                '<p>' . 
                    'Total size deleted from the adaptive images cache: ' . 
                    adaptive_images_plugin_file_size_human( $result['size'] ) . 
                '</p>', 
                'updated' 
            ); 

        } 



        // Calculate image cache size action.

        if ( $_GET['action'] == 'calculate-cache-size' && 
             wp_verify_nonce( $_GET['_wpnonce'], 'adaptive-images-calculate-cache-size' ) ) {

            $cache_path = adaptive_images_plugin_get_cache_directory_path();
            $cache_size = adaptive_images_plugin_dir_size( $cache_path ); 

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                'Calculate cache size <hr />' . 
                '<p>Total files in the adaptive images cache: ' . $cache_size['files'] . '</p>' .  
                '<p>Total directories in the adaptive images cache: ' . $cache_size['dirs'] . '</p>' .  
                '<p>' . 
                    'Total size of the adaptive images cache: ' . 
                    adaptive_images_plugin_file_size_human( $cache_size['size'] ) . 
                '</p>', 
                'updated' 
            ); 

        } 



        // Print plugin info action.

         if ( $_GET['action'] == 'print-debug-info' && 
              wp_verify_nonce( $_GET['_wpnonce'], 'adaptive-images-print-debug-info' ) ) {

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                'Debug information <hr />' . 
                adaptive_images_debug_general_info( FALSE ), 
                'updated' 
            ); 

        }



        // Print system info action.

         if ( $_GET['action'] == 'print-diagnostic-info' && 
              wp_verify_nonce( $_GET['_wpnonce'], 'adaptive-images-print-diagnostic-info' ) ) {

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                'Diagnostic information <hr />' . 
                adaptive_images_debug_diagnostics( FALSE ), 
                'updated' 
            ); 

        }

    }



    /**
     * Validates the adaptive images submitted settings.
     * 
     * @param $data array The submitted settings array.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return The sanitized and validated settings array.
     */

    function adaptive_images_admin_settings_sanitize ( $data ) {
    	
        // To avoid the bug of sanitizing twice the first time the option is created.

        if ( isset( $_REQUEST['sanitized'] ) && $_REQUEST['sanitized'] ) {

            return $data;

        }
        
        

        // Get the defaults just in case.

        $defaults = adaptive_images_plugin_get_default_settings();



        // Resolutions field array validation.
        
        if ( isset( $data['resolutions'] ) && is_array( $data['resolutions'] ) ) {
        	
        	// Option given as an array or already stored as an array.
        	
        	$resolutions_array = $data['resolutions'];
        	 
        } else if ( isset( $data['resolutions'] ) && is_string( $data['resolutions'] ) ) {
        	
        	// Option coming from settings page form field as a string.
        	
        	$resolutions = trim( $data['resolutions'] );
        	$resolutions_array = explode( ',', $resolutions );
        	
        } else {
        	
        	// Default case, option has never been set.
        	        	
        	$resolutions_array = $defaults['resolutions'];
        	
        }
	
        $resolutions_array_sanitized = array();

        for ( $k = 0, $length = count( $resolutions_array ); $k < $length; $k++ ) {

            $resolution = trim( $resolutions_array[$k] );
            $resolution = intval( $resolution );

            if ( $resolution > 0  ) {

                $resolutions_array_sanitized[] = $resolution;

            }

        }

        rsort( $resolutions_array_sanitized );

        if ( count( $resolutions_array_sanitized ) == 0 )  {

            $resolutions_array_sanitized = $defaults['resolutions'];

        }

        $data['resolutions'] = $resolutions_array_sanitized;



        // Landscape field validation.

        $landscape = isset( $data['landscape'] ) && $data['landscape'] == 'on' ? TRUE : FALSE;

        $data['landscape'] = $landscape;



        // Hidpi field validation.

        $hidpi = isset( $data['hidpi'] ) && $data['hidpi'] == 'on' ? TRUE : FALSE;

        $data['hidpi'] = $hidpi;



        // Hidpi field validation.

        $cdn_support = isset( $data['cdn-support'] ) && $data['cdn-support'] == 'on' ? TRUE : FALSE;

        $data['cdn-support'] = $cdn_support;



        // Cache field directory validation.

        $cache_directory = isset( $data['cache-directory'] ) ? trim( $data['cache-directory'] ) : '';
        
        if ( $cache_directory == '' ) {
	        
        	$cache_directory = $defaults['cache-directory'];
        	
        }
        
        $cache_directory = preg_replace( '/[^a-zA-Z\d-_\/]+/i', '', $cache_directory );
        $cache_directory = wp_normalize_path( $cache_directory );
        $cache_directory = untrailingslashit( $cache_directory );
        
        if ( ! adaptive_images_plugin_is_file_in_wp_content( $cache_directory ) ) {

            $cache_directory == $defaults['cache-directory'];

        }
                
        $data['cache-directory'] = $cache_directory;



        // Watched directories field validation.
                
        if ( isset( $data['watched-directories'] ) && is_array( $data['watched-directories'] ) ) {
        	 
        	// Option given as an array or already stored as an array.
        	 
        	$watched_directories_array = $data['watched-directories'];
        
        } else if ( isset( $data['watched-directories'] ) && is_string( $data['watched-directories'] ) ) {
        	 
        	// Option coming from settings page form field as a string.
        	 
        	$watched_directories_string = trim( $data['watched-directories'] );
        	$watched_directories_array = explode( "\n", $watched_directories_string );
        	 
        } else {
        	
        	// Default case, option has never been set.
        	        	
        	$watched_directories_array = $defaults['watched-directories'];
        	
        }

        $watched_directories_array_sanitized = array();

        foreach ( $watched_directories_array as $directory ) {
            
            $directory = preg_replace( '/[^a-zA-Z\d-_\/]+/i', '', $directory );
            $directory = wp_normalize_path( $directory );
            $directory = untrailingslashit( $directory );

            if ( $directory ) {

                $watched_directories_array_sanitized[] = $directory;
                
            }

        }

        $data['watched-directories'] = $watched_directories_array_sanitized;
        
        
        
        // Content types fields validation.
        
        if ( isset( $data['content-types-jpeg'] ) ||
        	 isset( $data['content-types-png'] )  ||
        	 isset( $data['content-types-gif'] ) ) {
        	
        	// Options comming from settings page form as checkbox values.
        	
     	 	$content_types = array();
        	 	
       	 	if ( isset( $data['content-types-jpeg'] ) && $data['content-types-jpeg'] == 'on' ) {
        	 		 
       	 		$content_types []= 'jpg';
       	 		$content_types []= 'jpeg';
       	 		unset( $data['content-types-jpeg'] );
        	 	
       	 	}
       	 	
       	 	if ( isset( $data['content-types-png'] ) && $data['content-types-png'] == 'on' ) {
        	 	
        		$content_types[] = 'png';
        		unset( $data['content-types-png'] );
        	 	
        	}
        	 	
        	if ( isset( $data['content-types-gif'] ) && $data['content-types-gif'] == 'on' ) {
        	 	
        		$content_types[] = 'gif';
        		unset( $data['content-types-gif'] );
        	
        	}
        	 	
        	$data['content-types'] = $content_types;
        	 
        }
                
        adaptive_images_plugin_check_empty_setting( $data, 'content-types' );
                


        // JPEG quality field validation.

        $jpeg_quality = isset( $data['jpeg-quality'] ) ? intval( $data['jpeg-quality'] ) : 0;

        if ( $jpeg_quality <= 0 || $jpeg_quality > 100 ) {
            
            $jpeg_quality = $defaults['jpeg-quality'];
        }

        $data['jpeg-quality'] = $jpeg_quality;



        // Sharpen field validation.

        $sharpen_images = isset( $data['sharpen-images'] ) && $data['sharpen-images'] == 'on' ? TRUE : FALSE;

        $data['sharpen-images'] = $sharpen_images;



        // Watch cache field validation.

        $watch_cache = isset( $data['watch-cache'] ) && $data['watch-cache'] == 'on' ? TRUE : FALSE;

        $data['watch-cache'] = $watch_cache;



        // Browser cache field validation.

        $browser_cache = isset( $data['browser-cache'] ) ? floatval( $data['browser-cache'] ) : -1;

        if ( $browser_cache < 0  ) {

            $browser_cache = $defaults['browser-cache'];

        }

        $data['browser-cache'] = $browser_cache;



        // Save plugin version.

        $data['version'] = adaptive_images_plugin_get_version();
        
        

        // Avoid the bug of sanitizing twice in the same request.

        $_REQUEST['sanitized'] = TRUE;



        // Notify user appropriately.

        $message = 
            'Adaptive Images &mdash; Settings updated. <hr />' . 
            '<p>&#10004; The plugin settings have been saved in the database.</p>';



        // Add the adaptive images .htaccess rewrite block.

        $result = adaptive_images_actions_htaccess_update( $data );

        if ( is_wp_error( $result ) ) {

            $error_data = $result->get_error_data();
            $htaccess = $error_data['htaccess'];
            $permissions = adaptive_images_plugin_file_permissions( $htaccess );

            $message .= 
                '<p>' . 
                    '&#10006; ' . $result->get_error_message() .
                '</p>' . 
                '<p>' . 
                    'This probably means a filesystem error or a permissions problem: ' . 
                    '<code>' . $htaccess . ' => ' . $permissions . '</code>.' . 
                '</p>' .
                '<p>' .
                    'Consider adding this code to your .htaccess file manually: ' . 
                    '<blockquote><pre>' . htmlspecialchars( trim( $error_data['rewrite'] ) ) . '</pre></blockquote>' .
                '</p>';

        } else {

            $message .=
                '<p>' . 
                    '&#10004; The .htaccess file has been successfully updated: ' . 
                    '<code>' . adaptive_images_plugin_get_htaccess_file_path() . '</code>.' .
                '</p>';

        }



        // Add the wp-content directory path to the settings array.
        
        $data['wp-content'] = WP_CONTENT_DIR;



        // Save user settings PHP file.

        $result = adaptive_images_actions_user_settings_php_save( $data );

        if ( is_wp_error( $result ) ) {

            $file = adaptive_images_plugin_get_user_settings_file_path();
            $permissions = adaptive_images_plugin_file_permissions( $file );
            
            $message .= 
                '<p>' . 
                    '&#10006; ' . $result->get_error_message() . 
                '</p>' . 
                '<p>' . 
                    'This probably means a filesystem error or a permissions problem. Please contact your system administrator on how to deal with this!' . 
                '</p>' . 
                '<p>' . 
                    'The user settings file permissions are: ' . 
                    '<code>' . 
                        $file . ' => ' . $permissions . 
                    '</code>.'. 
                '</p>' . 
                '<p>' . 
                    'The plugin will still be able to function but with its default settings until this problem is resolved.' .
                '</p>';

        } else {

            $message .= 
                '<p>' . 
                    '&#10004; The user settings PHP file  was successfully updated: ' . '<code>' . adaptive_images_plugin_get_user_settings_file_path() . '</code>.' .
                '</p>';

        }



        // Inform user accordingly. 
        
        add_settings_error( 
           'adaptive-images-settings', 
           'adaptive-images-settings-error', 
           $message,
           'updated' 
        );
        
        
        
        return $data;

    }

?>