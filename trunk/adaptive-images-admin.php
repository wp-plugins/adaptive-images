<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *      ALL THE ADMIN PLUGIN SETTINGS AREA FUNCTIONS                                                              *
     *      ============================================                                                              *
     *                                                                                                                *
     *      Nevma (info@nevma.gr)                                                                                     *
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
     * @return Nothing really!
     */

    function adaptive_images_admin_show_admin_css () { ?>

        <style type = "text/css">

            /* Make our admin notices look like other WordPress settings errors. */

            .adaptive-images-settings-error { font-weight: bold; }

        </style> <?php

    }



    /**
     * Adds a link to the adaptive images settings in the plugins listing page for convenience.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param array $links The plugin links array in the plugins listing page.
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_add_plugin_settings_link ( $links ) {
        
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
     * @return Nothing really!
     */

    function adaptive_images_admin_add_row_meta ( $links, $file ) {

        if ( $file != adaptive_images_plugin_get_name() ) {

            return $links;

        }
        
        $links[] = '<a href = "https://wordpress.org/plugins/adaptive-images/">' . 'Plugin page in WP repository' . '</a>';
        $links[] = '<a href = "http://wordpress.org/support/plugin/adaptive-images">' . 'Plugin support page in WP repository' . '</a>';

        return $links;

    }



    /**
     * Adds the plugin settings page to the admin area.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_add_options_page () {

        // Adds the plugin's options page as a submenu of the admin settings.

        $hook_name = add_options_page( 
            'Adaptive Images',                         // page title
            'Adaptive Images',                         // menu title
            'manage_options',                          // capability
            'adaptive-images',                         // menu slug
            'adaptive_images_admin_options_page_print' // print function callback
        );

        // Adds the action which adds the plugin admin actions.

       add_action( 'admin_head-' . $hook_name, 'adaptive_images_admin_settings_actions' ); 



    }



    /**
     * Registers the plugin settings.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_register_settings () {

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
           'Resolutions',                                   // title 
           'adaptive_images_admin_resolutions_field_print', // print function callback
           'adaptive-images',                               // plugin page
           'adaptive-images-settings'                       // section
        );

        // Adds the adaptive images cache directory field.

        add_settings_field( 
           'cache-directory',                                   // id
           'Cache directory',                                   // title 
           'adaptive_images_admin_cache_directory_field_print', // print function callback
           'adaptive-images',                                   // plugin page
           'adaptive-images-settings'                           // section
        );

        // Adds the adaptive images watched directories field.

        add_settings_field( 
           'watched-directories',                                   // id
           'Watched directories',                                   // title 
           'adaptive_images_admin_watched_directories_field_print', // print function callback
           'adaptive-images',                                       // plugin page
           'adaptive-images-settings'                               // section
        );

        // Adds the adaptive images JPEG quality field.

        add_settings_field( 
           'jpeg-quality',                                   // id
           'JPEG quality',                                   // title 
           'adaptive_images_admin_jpeg_quality_field_print', // print function callback
           'adaptive-images',                                // plugin page
           'adaptive-images-settings'                        // section
        );

        // Adds the adaptive images sharpen field.

        add_settings_field( 
           'sharpen-images',                                   // id
           'Sharpen images',                                   // title 
           'adaptive_images_admin_sharpen_images_field_print', // print function callback
           'adaptive-images',                                  // plugin page
           'adaptive-images-settings'                          // section
        );

        // Adds the adaptive images watch cache (for stale images) field.

        add_settings_field( 
           'watch-cache',                                   // id
           'Watch cache',                                   // title 
           'adaptive_images_admin_watch_cache_field_print', // print function callback
           'adaptive-images',                               // plugin page
           'adaptive-images-settings'                       // section
        );

        // Adds the adaptive images browser cache duration field.

        add_settings_field( 
           'browser-cache',                                   // id
           'Browser cache',                                   // title 
           'adaptive_images_admin_browser_cache_field_print', // print function callback
           'adaptive-images',                                 // plugin page
           'adaptive-images-settings'                         // section
        );

    }



    /**
     * Prints the resolutions settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_resolutions_field_print () {

        $options = get_option( 'adaptive-images' ); 

        adaptive_images_plugin_check_empty_setting( $options, 'resolutions' ); ?>

        <input type = "text" id = "adaptive-images[resolutions]" name = "adaptive-images[resolutions]" value = "<?php echo implode( ', ', $options['resolutions'] ); ?>" size = "25" /> 

        A comma separated list of device widths. <?php

    }



    /**
     * Prints the cache directory settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_cache_directory_field_print () {

        $options = get_option( 'adaptive-images' ); 

        adaptive_images_plugin_check_empty_setting( $options, 'cache-directory' ); ?>

        <input type = "text" id = "adaptive-images[cache-directory]" name = "adaptive-images[cache-directory]" value = "<?php echo $options['cache-directory']; ?>" size = "25" /> 
        
        Directory inside /wp-content to store the image cache. 
        <br /><br />

        <small>
            Current path on server: 
            <?php 
                $file = trailingslashit( WP_CONTENT_DIR ) . $options['cache-directory'];
                echo $file;
            ?>.
        </small> <?php

    }



    /**
     * Prints the watched directories settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_watched_directories_field_print () {

        $options = get_option( 'adaptive-images' ); 

        adaptive_images_plugin_check_empty_setting( $options, 'watched-directories' ); ?>

        <textarea id = "adaptive-images[watched-directories]" name = "adaptive-images[watched-directories]" cols = "60" rows = "5"><?php echo implode( "\n", $options['watched-directories'] ); ?></textarea>

        <br /><br />

        <small>Directories to watch for images to resize and adapt for mobile devices. Has to be relative paths inside your installation.</small> <?php

    }



    /**
     * Prints the cache JPEG quality settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_jpeg_quality_field_print () {

        $options = get_option( 'adaptive-images' ); 

        adaptive_images_plugin_check_empty_setting( $options, 'jpeg-quality' ); ?>

        <select type = "text" id = "adaptive-images[jpeg-quality]" name = "adaptive-images[jpeg-quality]">
            <?php for ( $k = 100; $k >= 5; $k -= 5 ) : ?> 
                <option value = "<?php echo $k; ?>" <?php echo $options['jpeg-quality'] == $k ? 'selected = "selected"' : ''; ?>><?php echo $k; ?></option>
            <?php endfor; ?>
        </select> 

        Compression level of JPEG images, 100 means best quality but biggest file size. 
        <br /><br />
        <small>Usuallly a 60 to 70 JPEG compression level works fine for the human eye and achieves very small file sizes.</small> <?php

    }



    /**
     * Prints the sharpen settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_sharpen_images_field_print () {

        $options = get_option( 'adaptive-images' ); 

        adaptive_images_plugin_check_empty_setting( $options, 'sharpen-images' ); ?>

        <label for = "adaptive-images[sharpen-images]">
            
            <input type = "checkbox" id = "adaptive-images[sharpen-images]" name = "adaptive-images[sharpen-images]" <?php echo $options['sharpen-images'] ? 'checked = "checked"' : ''; ?> /> 

            Yes, sharpen JPEG images after compressiing and resizing, in order to reduce blur.

        </label> <?php

    }



    /**
     * Prints the watch cache settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_watch_cache_field_print () {

        $options = get_option( 'adaptive-images' ); 

        adaptive_images_plugin_check_empty_setting( $options, 'watch-cache' ); ?>

        <label for = "adaptive-images[watch-cache]">
            
            <input type = "checkbox" id = "adaptive-images[watch-cache]" name = "adaptive-images[watch-cache]" <?php echo $options['watch-cache'] ? 'checked = "checked"' : ''; ?> /> 

            Yes, check if an image has been updated in the meantime, in order to generate it again.

        </label> <?php

    }



    /**
     * Prints the browser cache settings field.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_browser_cache_field_print () {

        $options = get_option( 'adaptive-images' ); 

        adaptive_images_plugin_check_empty_setting( $options, 'browser-cache' ); ?>

        <select type = "text" id = "adaptive-images[browser-cache]" name = "adaptive-images[browser-cache]">
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

        How long should browsers be instructed to cache images. 
        <br /><br />
        <small>Unless you have a very special need and/or you know what you are doing, set this to as high a value as you can.</small> <?php

    }



    /**
     * Prints the contents of the plugin options page.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_options_page_print () {  ?>

        <div class = "wrap">

            <h2>Adaptive Images</h2>

            <form method = "post" action = "options.php">

            <h3>Image cache settings</h3>

                <?php 
                    // Print plugin settings form.

                    settings_fields( 'adaptive-images-settings' ); 
                    do_settings_sections( 'adaptive-images' ); 
                    submit_button( 'Save settings' ); 
                ?>

                <?php // Override default referer because it might contain other GET data on it. ?>

                <input type = "hidden" name = "_wp_http_referer" value = "options-general.php?page=adaptive-images" />

            </form>

            <br />
            <hr />

            <h3>Other actions</h3>

            <style type = "text/css">

                .adaptive-images-admin-table { margin: 0; padding: 0; border: none; }

                    .adaptive-images-admin-table td { margin: 0; padding: 0 50px 0 0; vertical-align: top; border: none; white-space: nowrap; }

            </style>

            <table class = "adaptive-images-admin-table">
                <tbody>
                   <tr>
                        <td>
                           
                            Cleanup the image cache. 
                            <br /><br />
                            <a class = "button-primary" href = "options-general.php?page=adaptive-images&action=cleanup-image-cache&_wpnonce=<?php echo wp_create_nonce( 'adaptive-images-cleanup-image-cache' ); ?>">Cleanup image cache</a> 
                            <br />
                            <small>(might take some time)</small>

                        </td>
                        <td>
                           
                            Calculate total size of image cache. 
                            <br /><br />
                            <a class = "button-primary" href = "options-general.php?page=adaptive-images&action=calculate-cache-size&_wpnonce=<?php echo wp_create_nonce( 'adaptive-images-calculate-cache-size' ); ?>">Calculate cache size</a> 
                            <br />
                            <small>(might take some time)</small>

                        </td>
                        <td>
                            
                            Print plugin debug information. 
                            <br /><br />
                            <a class = "button-primary" href = "options-general.php?page=adaptive-images&action=print-debug-info&_wpnonce=<?php echo wp_create_nonce( 'adaptive-images-print-debug-info' ); ?>">Print debug info</a> 
                            <br />
                            <small>(this is quite quick)</small>

                        </td>
                   </tr> 
                </tbody>
            </table>


            <br />
            <hr />

            <h3>Contact the developers</h3>

            <p>
                Thank you so much for trying out this plugin. <br />
                We are totally commited to idea of reducing image sizes for mobile devices, but without compromising their quality at the same time <br />
                But we need your help in order to achieve this. Please, do not hesitate to report any problems and send us any suggestions you have at the plugin support page. We really appreciate it.
                <br />
                <br />
                <strong><a href = "https://wordpress.org/support/plugin/adaptive-images">Adaptive Images plugin support page</a></strong>
                <br />
                <br />
                Many thanks, 
                <br />
                The development team!
            </p> 

        </div> <?php

    }



    /**
     * Takes care of the plugin settings page actions.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!.
     */

    function adaptive_images_admin_settings_actions () {

        // Cleanup image cache action.

        if ( isset( $_GET['action'] ) && 
             $_GET['action'] == 'cleanup-image-cache' && 
             wp_verify_nonce( $_GET['_wpnonce'], 'adaptive-images-cleanup-image-cache' ) ) {

            $cache_path = adaptive_images_plugin_get_cahe_directory_path();

            $result = adaptive_images_actions_rmdir_recursive( $cache_path ); 

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                'Cleanup image cache <hr />' . 
                '<p>Total files deleted from the adaptive images cache: ' . $result['files'] . '</p>' .  
                '<p>Total directories deleted from the adaptive images cache: ' . $result['dirs'] . '</p>' .  
                '<p>Total size deleted from the adaptive images cache: ' . adaptive_images_plugin_file_size_human( $result['size'] ) . '</p>', 
                'updated' 
            ); 

        } 



        // Calculate image cache size action.

        if ( isset( $_GET['action'] ) && 
             $_GET['action'] == 'calculate-cache-size' && 
             wp_verify_nonce( $_GET['_wpnonce'], 'adaptive-images-calculate-cache-size' ) ) {

            $cache_path = adaptive_images_plugin_get_cahe_directory_path();
            $cache_size = adaptive_images_plugin_dir_size( $cache_path ); 

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                'Calculate cache size <hr />' . 
                '<p>Total files in the adaptive images cache: ' . $cache_size['files'] . '</p>' .  
                '<p>Total directories in the adaptive images cache: ' . $cache_size['dirs'] . '</p>' .  
                '<p>Total size of the adaptive images cache: ' . adaptive_images_plugin_file_size_human( $cache_size['size'] ) . '</p>', 
                'updated' 
            ); 

        } 



        // Print plugin info action.

        if ( isset( $_GET['action'] ) && 
             $_GET['action'] == 'print-debug-info' && 
             wp_verify_nonce( $_GET['_wpnonce'], 'adaptive-images-print-debug-info' ) ) {

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                'Debug info <hr />' . 
                adaptive_images_admin_debug_info( FALSE ), 
                'updated' 
            ); 

        } 

    }



    /**
     * Validates the adaptive images submitted settings.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return The sanitized and validated data.
     */

    function adaptive_images_admin_settings_sanitize ( $data ) {

        // To avoid the bug of sanitizing twice the first time the option is created.

        if ( $data['sanitized'] ) {

            return $data;

        }



        // Get the defaults just in case.

        $defaults = adaptive_images_plugin_get_default_settings();



        // Resolutions array validation.

        $resolutions = trim( $data['resolutions'] );
        $resolutions_array = explode( ',', $resolutions );
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

        adaptive_images_plugin_check_empty_setting( $data, 'resolutions' );



        // Cache directory validation.

        $cache_directory = trim( $data['cache-directory'] );
        $cache_directory = preg_replace( '/[^a-zA-Z\d-_\/]+/i', '', $cache_directory );
        $cache_directory = wp_normalize_path( $cache_directory );
        $cache_directory = untrailingslashit( $cache_directory );

        if ( ! adaptive_images_plugin_is_file_in_wp_content( $cache_directory ) ) {

            $cache_directory == $defaults['cache-directory'];

        }

        $data['cache-directory'] = $cache_directory;

        adaptive_images_plugin_check_empty_setting( $data, 'cache-directory' );



        // Watched directories validation.

        $watched_directories_string = trim( $data['watched-directories'] );
        $watched_directories_array = explode( "\n", $watched_directories_string );
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

        adaptive_images_plugin_check_empty_setting( $data, 'watched-directories' );



        // JPEG quality validation.

        $jpeg_quality = intval( $data['jpeg-quality'] );

        if ( $jpeg_quality == 0 ) {
            
            $jpeg_quality = $defaults['jpeg-quality'];
        }

        $data['jpeg-quality'] = $jpeg_quality;

        adaptive_images_plugin_check_empty_setting( $data, 'jpeg-quality' );



        // Sharpen validation.

        $sharpen_images = isset( $data['sharpen-images'] ) ? $data['sharpen-images'] == 'on' : $defaults['sharpen-images'];

        $data['sharpen-images'] = $sharpen_images;

        adaptive_images_plugin_check_empty_setting( $data, 'sharpen-images' );




        // Watch cache validation.

        $watch_cache = isset( $data['watch-cache'] ) ? $data['watch-cache'] == 'on' : $defaults['watch-cache'];

        $data['watch-cache'] = $watch_cache;

        adaptive_images_plugin_check_empty_setting( $data, 'watch-cache' );



        // Browser cache validation.

        $browser_cache = floatval( $data['browser-cache'] );

        if ( $browser_cache < 0.125  ) {

            $browser_cache = $defaults['browser-cache'];

        }

        $data['browser-cache'] = $browser_cache;

        adaptive_images_plugin_check_empty_setting( $data, 'browser-cache' );



        // Save plugin version.

        $data['version'] = adaptive_images_plugin_get_version();

        // To avoid the bug of sanitizing twice the first time the option is created.

        $data['sanitized'] = TRUE;



        // Notify user appropriately.

        $message = 'Adaptive Images &mdash; Settings updated. <hr /> <p>The settings have been saved in the database.</p>';



        // Add the adaptive images htaccess rewrite block.

        $result = adaptive_images_actions_update_htaccess( $data );

        if ( is_wp_error( $result ) ) {

            $error_data = $result->get_error_data();
            $htaccess = $error_data['htaccess'];
            $permissions = adaptive_images_plugin_file_permissions( $htaccess );

            $message .= 
                '<p>' . 
                    $result->get_error_message() . ' ' .
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
                    'The htaccess file was successfully updated: ' . 
                    '<code>' . adaptive_images_plugin_get_htaccess_file_path() . '</code>.' .
                '</p>';

        }



        // Save user settings PHP file.

        $result = adaptive_images_actions_save_user_settings( $data );

        if ( is_wp_error( $result ) ) {

            $file = adaptive_images_plugin_get_user_settings_file_path();
            $permissions = adaptive_images_plugin_file_permissions( $file );
            
            $message .= 
                '<p>' . 
                    $result->get_error_message() . ' ' . 
                    'This probably means a filesystem error or a permissions problem.' . 
                    '<code>' . $file . ' => ' . $permissions . '</code>. <br/>'. 
                '</p>' . 
                '<p>' . 
                    'The plugin will still be able to function but with its default settings until this problem is resolved.' .
                '</p>';

        } else {

            $message .= 
                '<p>' . 
                    'The user settings PHP file  was successfully updated: ' . '<code>' . adaptive_images_plugin_get_user_settings_file_path() . '</code>.' .
                '</p>';

        }



        add_settings_error( 
           'adaptive-images-settings', 
           'adaptive-images-settings-error', 
           $message,
           'updated' 
        );



        return $data;

    }



    /**
     * Prints useful debug information for the plugin.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param bool $echo Whether to echo the result or return it as a string.
     * 
     * @return Nothing really!
     */

    function adaptive_images_admin_debug_info ( $echo = true ) {

        $options = get_option( 'adaptive-images' );
        
        

        // PHP GD image library debug info.

        $message = '<p>PHP GD library ';

        if ( adaptive_images_plugin_is_gd_extension_installed() ) {

            $message .= ' is installed OK.';

        } else {

            $message .= ' is NOT installed.';

        }

        $message .= '</p>';



        // Image cache directory debug info.

        $cache_path = adaptive_images_plugin_get_cahe_directory_path();

        $message .= '<p>Image cache directory ' . ( file_exists( $cache_path ) ? 'is OK' : 'has NOT been created' ) . '.';
        $message .= file_exists( $cache_path ) ? '<br /> <code>' . $cache_path . ' => ' . adaptive_images_plugin_file_permissions( $cache_path ) . '</code>' : '';
        $message .= '</p>';



        // Htaccess file availability

        $message .= '<p>Installation .htaccess file is ' . ( adaptive_images_plugin_is_htaccess_available() ? '' : 'NOT' ) . ' available.</p>';



        // Image cache settings dump.

        $message .= '<p>Adaptive images settings dump:</p>';
        $message .= '<pre>';
        ob_start();
        var_dump( $options );
        $message .= ob_get_clean();
        $message .= '</pre>';



        // Echo debug info or return it.

        if ( $echo ) {
            
            echo $message;

        } else {

            return $message;

        }

    }

?>