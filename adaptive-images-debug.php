<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *                                                                                                                *
     *      PLUGIN DEBUG/DIAGNOSTIC FUNCTIONS                                                                         *
     *      =================================                                                                         *
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
     * Returns whether the PHP GD image library is installed.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return boolean Whether the PHP GD image library is installed.
     */

    function adaptive_images_debug_is_gd_available () {

        return extension_loaded( 'gd' ) && function_exists( 'gd_info' );

    }



    /**
     * Returns whether the plugin options have been set in the database.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return boolean Whether the plugin options are set or not.
     */

    function adaptive_images_debug_are_settings_saved () {

        return adaptive_images_plugin_get_options() !== false ;

    }



    /**
     * Checks whether the installation .htaccess file is actually writable.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return boolean Whether the installation htaccess file is actually writable.
     */

    function adaptive_images_debug_is_htaccess_writeable () {

        $htaccess = adaptive_images_plugin_get_htaccess_file_path();

        return
            ( ! file_exists( $htaccess ) && @fopen( $htaccess, 'w' ) ) ||
            (   file_exists( $htaccess ) && is_writable( $htaccess ) );

    }


    
    /**
     * Returns whether the installation .htaccess file is available, writeable and that it has been updated with the 
     * contents that are necessary for the plugin to work.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return boolean Whether the htaccess file is updated.
     */

    function adaptive_images_debug_is_htaccess_updated () {

        // Do the check and inform user.

        $htaccess                      = adaptive_images_plugin_get_htaccess_file_path();
        $htaccess_contents             = file_get_contents( $htaccess );
        $htaccess_rewrite_block_regexp = '/# BEGIN Adaptive Images.*# END Adaptive Images\n/s';
        $htaccess_contents_updated     = preg_match( $htaccess_rewrite_block_regexp, $htaccess_contents );

        return $htaccess_contents_updated;

    }



    /**
     * Returns whether the cache directory is available.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return boolean Whether the cache directory is available.
     */

    function adaptive_images_debug_is_cache_available () {

        $cache_path = adaptive_images_plugin_get_cache_directory_path();

        return file_exists( $cache_path ) && is_writable( $cache_path );

    }



    /**
     * Performs the check for the PHP GD image library being installed.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return array An associative array with the results of the check.
     */
    
    function adaptive_images_debug_perform_check_gd () {

        $icons = array( 'true' => '&#10004;', 'false' => '&#10006;' );

        

        // Check if PHP GD image library is installed and available.
                
        $gd_extension_installed = adaptive_images_debug_is_gd_available();

        $result = array( 
           'result'  => $gd_extension_installed,
           'message' => 

                $gd_extension_installed ? 

                    '<p>' . 
                        $icons['true'] . ' The PHP GD library is installed.' .
                    '</p>' :

                    '<p>' 
                        . $icons['false'] . ' The PHP GD image library is not installed in your server.' .
                    '</p>' .
                    '<p>' . 
                        'This is absolutely necessary for the plugin to function properly. Please deactivate the plugin immediately and activate it after having installed the PHP GD image library. <br />' . 
                    '</p>' .
                    '<p>' . 
                        'You should probably contact your system administrator about this. You may find more information about it at the <a href = "http://php.net/manual/en/book.image.php">PHP GD image library page</a>, in the php.net website.' .
                    '</p>'
        );

        return $result;

    }



    /**
     * Performs the check for the user settings haveing been saved.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return array An associative array with the results of the check.
     */
    
    function adaptive_images_debug_perform_check_settings () {

        $icons = array( 'true' => '&#10004;', 'false' => '&#10006;' );



        // Check if user settings are saved.
                
        $user_settings_saved = adaptive_images_debug_are_settings_saved();

        $result = array( 
           'result'  => $user_settings_saved,
           'message' => 

                $user_settings_saved ? 

                    '<p>' .
                        $icons['true'] . ' Your plugin settings have been saved correctly.'  . 
                    '</p>' :

                    '<p>' .
                        $icons['false'] . ' Your plugin settings have not been correctly saved yet!' .
                    '</p>'.
                    '<p>' . 
                        'The plugin is active but its settings have not been initialized, so it is not actually functioning yet.' . 
                    '</p>' . 
                    '<p>' . 
                        'Nothing to worry about, just go to <a href = "options-general.php?page=adaptive-images">Adaptive Images Settings</a> page in order to save your settings and start actually using the plugin.'.
                    '</p>'

        );

        return $result;

    }



    /**
     * Performs the check for the htaccess file have been updated.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return array An associative array with the results of the check.
     */
    
    function adaptive_images_debug_perform_check_htaccess () {

        $icons = array( 'true' => '&#10004;', 'false' => '&#10006;' );


        
        // Check if htaccess file is ok or at least writeable
        
        $htaccess = adaptive_images_plugin_get_htaccess_file_path();

        $htaccess_updated   = adaptive_images_debug_is_htaccess_updated();
        $htaccess_writeable = adaptive_images_debug_is_htaccess_writeable();
        $permissions        = adaptive_images_plugin_file_permissions( $htaccess );

        $result = array( 
            'result'  => $htaccess_updated, 
            'message' => 
                $htaccess_updated ?

                    '<p>' .
                        $icons['true'] . ' Installation .htaccess file is properly updated.' .
                    '</p>' :

                    '<p>' .
                        $icons['false'] . ' Installation .htaccess file is not properly updated.' . 
                        ( ! $htaccess_writeable ? ' The file seems to be not writeable!' : '' ) .
                    '</p>' .
                    '<p>' .
                        'Try to save the plugin settings once again in <a href = "options-general.php?page=adaptive-images">Adaptive Images Settings</a> and, if the problem persists, contact your system administrator.' . 
                    '</p>' . 
                    '<p>' . 
                        'The .htaccess file permissions currently are: <code>' . $htaccess . ' => ' . $permissions . '</code>.' . 
                    '</p>' 
        );


        return $result;

    }



    /**
     * Performs the check for the image cache directories having been created and are available.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return array An associative array with the results of the check.
     */
    
    function adaptive_images_debug_perform_check_cache () {

        $icons = array( 'true' => '&#10004;', 'false' => '&#10006;' );



        // Attempt to create the image cache directory.
        
        $cache_path = adaptive_images_plugin_get_cache_directory_path();

        if ( ! is_dir( $cache_path ) ) {

            @mkdir( $cache_path, 0755, true );

        }

        // Proceed with the checks.

        $cache_ok = adaptive_images_debug_is_cache_available();

        $cache_exists    = file_exists( $cache_path );
        $cache_writeable = is_writable( $cache_path );

        $wp_content_exists    = file_exists( WP_CONTENT_DIR );
        $wp_content_writeable = is_writable( WP_CONTENT_DIR );

        $cache_parent_path      = dirname( $cache_path );
        $cache_parent_exists    = file_exists( $cache_parent_path );
        $cache_parent_writeable = is_writable( $cache_parent_path );

        $message = 

            $cache_ok ? 

                '<p>' .
                    $icons['true'] . ' Image cache directory has been created and is writeable.' .
                '</p>' :

                '<p>' .
                    $icons['false'] . ' Image cache directory has not been created or is not writeable.' .
                '</p>';
            

        if ( ! $cache_ok ) {

            // Image cache directory not OK.

            if ( $cache_exists && ! $cache_writeable ) {

                // Reason: cache directory is not writeable.

                $message .= 
                    '<p>' . 
                        'It seems that the directory is not writeable, which is probably a filesystem permissions issue.' . 
                    '</p>' . 
                    '<p>' . 
                        'Please contact your system administrator on how to deal with this!' . 
                    '</p>' .
                    (
                        $cache_exists ? 
                            '<p>' .
                                'The cache directory permissions are: ' . 
                                '<code>' . 
                                    $cache_path . ' => ' . 
                                    adaptive_images_plugin_file_permissions( $cache_path ) . 
                                '</code>' .
                            '</p>' : 
                            ''
                    );

            } elseif ( ! $wp_content_writeable ) {
                    
                // Reason: /wp-content directory is not writeable.
                
                $message .= 
                    '<p>' . 
                        'It seems that your &quot;/wp-content&quot; directory is not writeable, which is a filesystem permissions issue.' .
                    '</p>' . 
                    '<p>' . 
                        'Please contact your system administrator on how to deal with this!' . 
                    '</p>' . 
                    '<p>' . 
                        'The &quot;/wp-content&quot; directory permissions are: ' . 
                        '<code>' . 
                            WP_CONTENT_DIR . ' => ' . 
                            adaptive_images_plugin_file_permissions( WP_CONTENT_DIR ) . 
                        '</code>' .
                    '</p>';
            
            } elseif ( ! $cache_parent_writeable ) {
                    
                // Reason: cache parent directory is not writeable.
                
                $message .= 
                    '<p>' . 
                        'It seems that your &quot;' . $cache_parent_path . '&quot; directory is not writeable, which is a filesystem permissions issue.' .
                    '</p>' . 
                    '<p>' . 
                        'Please contact your system administrator on how to deal with this!' . 
                    '</p>' . 
                    '<p>' . 
                        'This directory&apos;s permissions are: ' . 
                        '<code>' . 
                            $cache_parent_path . ' => ' . 
                            adaptive_images_plugin_file_permissions( $cache_parent_path ) . 
                        '</code>' .
                    '</p>';
            
            } else {

                // Reason: unexpected and unknown.
                
                $message .= 
                    '<p>' .
                        'The reason for this is so mysterious that it could not be detected.' . 
                    '</p>';

            }
            
        }

        $result = array( 
            'result'  => $cache_ok, 
            'message' => $message 
        );

        return $result;

    }



    /**
     * Checks if the PHP GD image library is available in the server and informs the user in the admin.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_check_gd_available_init () {

        $checks = adaptive_images_debug_perform_checks();

        // Do the check and inform user.

        if ( ! adaptive_images_debug_is_gd_available() ) {

            add_action( 'admin_notices', 'adaptive_images_debug_check_gd_available_init_message' );

        }

    }



    /**
     * Adds the admin notice error that informs the user when the check for the PHP GD has failed. It is done via an
     * admin notice and not via the settings errors, because in some pages the settings errors are called by the system
     * itself and this results in being called multiple times.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_check_gd_available_init_message () {

        $check = adaptive_images_debug_perform_check_gd();

        echo 
            '<div class = "error settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    'Adaptive Images' . 
                '</p>' . 
                '<hr />' . 
                $check['message'] . 
            '</div>';

    }



    /**
     * Checks if the plugin settings have been saved for the first tiimage cache directories have been created and are 
     * available and inform the user accirdingly.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_check_settings_saved_init () {

        // If options have not been saved yet then do not check.

        if ( ! adaptive_images_debug_are_settings_saved() ) {

            add_action( 'admin_notices', 'adaptive_images_debug_check_settings_saved_init_message' );

        }

    }



    /**
     * Adds the admin notice error that informs the user when the check for the settings has failed. It is done via an
     * admin notice and not via the settings errors, because in some pages the settings errors are called by the system
     * itself and this results in being called multiple times.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_check_settings_saved_init_message () {

        $check = adaptive_images_debug_perform_check_settings();

        echo 
            '<div class = "error settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    'Adaptive Images' . 
                '</p>' .
                '<hr />' . 
                $check['message'] . 
            '</div>';

    }



    /**
     * Checks if the plugin image cache directories have been created and are available and inform the user accirdingly.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_check_cache_available_init () {

        // If options have not been saved yet then do not check.
        
        if ( ! adaptive_images_debug_are_settings_saved() ) {

            return;

        }

        if ( ! adaptive_images_debug_is_cache_available() ) {

            add_action( 'admin_notices', 'adaptive_images_debug_check_cache_available_init_message' );

        }

    }



    /**
     * Adds the admin notice error that informs the user when the check for the cache readiness has failed. It is done 
     * via an admin notice and not via the settings errors, because in some pages the settings errors are called by the 
     * system itself and this results in being called multiple times.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_check_cache_available_init_message () {

        $check = adaptive_images_debug_perform_check_cache();

        echo 
            '<div class = "error settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    'Adaptive Images' . 
                '</p>' .
                '<hr />' . 
                $check['message'] . 
            '</div>';

    }



    /**
     * Checks if the installation .htaccess file is available, writeable and that it has been updated with the contents
     * which are necessary for the plugin to work.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_check_htaccess_updated_init () {

        // If options have not been saved yet then do not check.
        
        if ( ! adaptive_images_debug_are_settings_saved() ) {

            return;

        }

        if ( ! adaptive_images_debug_is_htaccess_updated() ) {

            add_action( 'admin_notices', 'adaptive_images_debug_check_htaccess_updated_init_message' );

        }

    }



    /**
     * Adds the admin notice error that informs the user when the check for the .htaccess has failed. It is done via an
     * admin notice and not via the settings errors, because in some pages the settings errors are called by the system
     * itself and this results in being called multiple times.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_check_htaccess_updated_init_message () {

        $check = adaptive_images_debug_perform_check_htaccess();

        echo 
            '<div class = "error settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    'Adaptive Images' . 
                '</p>' . 
                '<hr />' . 
                $check['message'] . 
            '</div>';

    }



    /**
     * Performs all necessary checks for the plugin's functionality.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return array An associative array with the results of the checks.
     */
    
    function adaptive_images_debug_perform_checks () {

        $icons = array( 'true' => '&#10004;', 'false' => '&#10006;' );



        // The results array to return.
        
        $results = array();
        
        // Check if PHP GD image library is installed and available.
                
        $results['gd'] = adaptive_images_debug_perform_check_gd();

        // Check if user settings are saved alright.

        $results['settings'] = adaptive_images_debug_perform_check_settings();

        // Check if image cache directory is created and writeable or its parent directory is writeable.
        
        $results['cache'] = adaptive_images_debug_perform_check_cache();
        
        // Check if htaccess file is ok or at least writeable
        
        $results['htaccess'] = adaptive_images_debug_perform_check_htaccess();



        // Do a global check in the end. 
        
        $results['all'] = $results['gd']      ['result'] && 
                          $results['settings']['result'] && 
                          $results['cache']   ['result'] && 
                          $results['htaccess']['result']; 
        
        return $results;

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

    function adaptive_images_debug_general_info ( $echo = true ) {

        // Perform all necessary tests.
        
        $checks = adaptive_images_debug_perform_checks();

        // PHP GD image library.

        $message .= $checks['gd']['message'];

        // User settings.

        $message .= $checks['settings']['message'];

        // Image cache directory.

        $message .= $checks['cache']['message'];

        // Htaccess file updated.
         
        $message .= $checks['htaccess']['message'];



        // Echo debug info or return it.

        if ( $echo ) {
            
            echo $message;

        } else {

            return $message;

        }

    }



    /**
     * Gets all kinds of system installation info. Kudos to WP-Migrate-DB and Send-System-Info plugins for the most of 
     * this.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_debug_diagnostics ( $echo = true ) {

        global $table_prefix;
        global $wpdb;



        // Collect diagnostic information.

        $debug = array();



        // Adaptive Images settings.

        $message .= '<pre style = "padding: 0; margin: 0;">';
        $options = adaptive_images_plugin_get_options();
        ob_start();
        var_dump( $options );
        $message .= ob_get_clean();
        $message .= '</pre>';

        $debug['Adaptive Images Settings'] = $message;



        // Web server info.

        $debug['Web Server']          = esc_html( $_SERVER['SERVER_SOFTWARE'] );
        $debug['PHP']                 = esc_html( phpversion() );
        $debug['PHP Time Limit']      = esc_html( ini_get( 'max_execution_time' ) );
        $debug['PHP Memory Limit']    = esc_html( ini_get( 'memory_limit' ) );
        $debug['PHP Post Max Size']   = esc_html( ini_get( 'post_max_size' ) );
        $debug['PHP Upload Max Size'] = esc_html( ini_get( 'upload_max_filesize' ) );
        $debug['PHP Max Input Vars']  = esc_html( ini_get( 'max_input_vars' ) );
        $debug['PHP Display Errors']  = ini_get( 'display_errors' ) == TRUE ? 'Yes' : 'No';
        $debug['PHP Error Log']       = ini_get( 'error_log' ) == TRUE ? 'Yes' : 'No';
        if ( $suhosin_limit = ini_get( 'suhosin.post.max_value_length' ) ) {
            $debug['Suhosin Post Max Value'] = esc_html( $suhosin_limit );
        }
        if ( $suhosin_limit = ini_get( 'suhosin.request.max_value_length' ) ) {
            $debug['Suhosin Request Max Value'] = esc_html( $suhosin_limit );
        }
        $debug['MySQL'] = esc_html( empty( $wpdb->use_mysqli ) ? 
            mysql_get_server_info() : 
            mysqli_get_server_info( $wpdb->dbh ) 
        );
        $debug['MySQL Ext/mysqli']   = empty( $wpdb->use_mysqli ) ? 'No' : 'Yes';
        $debug['MySQL Table Prefix'] = esc_html( $table_prefix );
        $debug['MySQL DB Charset']   = esc_html( DB_CHARSET );
        $debug['WordPress']          = get_bloginfo( 'version' );
        $debug['WP Multisite']       = ( is_multisite() ) ? 'Yes' : 'No';
        $debug['WP Debug Mode']      = esc_html( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No' );
        $debug['WP Site url']        = esc_html( site_url() );
        $debug['WP WP Home url']     = esc_html( home_url() );
        $debug['WP Permalinks']      = esc_html( get_option( 'permalink_structure' ) );
        $debug['WP content dir']     = esc_html( WP_CONTENT_DIR );
        $debug['WP content url']     = esc_html( WP_CONTENT_URL );
        $debug['WP plugin dir']      = esc_html( WP_PLUGIN_DIR );
        $debug['WP plugin url']      = esc_html( WP_PLUGIN_URL );
        $debug['WP Locale']          = esc_html( get_locale() );
        $debug['WP Memory Limit']    = esc_html( WP_MEMORY_LIMIT );
        $debug['WP Max Upload Size'] = esc_html( adaptive_images_plugin_file_size_human( wp_max_upload_size() ) );



        // Active system plugins.

        $active_plugins = ( array ) get_option( 'active_plugins', array() );

        $active_plugins_output = '';

        if ( count( $active_plugins ) > 0 ) {

            foreach ( $active_plugins as $plugin ) {

                $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

                $active_plugins_output .= 
                    $plugin_data['Name'] . 
                    ' v.' . $plugin_data['Version'] . 
                    ' by ' . $plugin_data['AuthorName'] . 
                    '<br />';

            }
        }

        $debug['WP Active plugins'] = $active_plugins_output;



        // Multisite (network) plugins.

        if ( is_multisite() ) {

            $network_active_plugins = wp_get_active_network_plugins();

            $active_plugins_output = '';

            if ( count( $network_active_plugins ) > 0 ) {

                foreach ( $network_active_plugins as $plugin ) {

                    $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

                    $active_plugins_output .= 
                        $plugin_data['Name'] . 
                        ' v.' . $plugin_data['Version'] . 
                        ' by ' . $plugin_data['AuthorName'] . 
                        '<br />';
                }
            }

            $debug['WP Network active plugins'] = $active_plugins_output;

        }



        // Must-use plugins.

        $mu_plugins = wp_get_mu_plugins();

        $active_plugins_output = '';

        if ( count( $mu_plugins ) > 0 ) {
            
            foreach ( $mu_plugins as $plugin ) {

                $plugin_data = get_plugin_data( $plugin );

                $active_plugins_output .= 
                    $plugin_data['Name'] . 
                    ' v.' . $plugin_data['Version'] . 
                    ' by ' . $plugin_data['AuthorName'] . 
                    '<br />';
            }
        }

        $debug['WP MU plugins'] = $active_plugins_output;



        // Create diagnostic output HTML table.

        $debug_output = '<table class = "adaptive-images-debug-table"><tbody>';

        foreach ( $debug as $key => $value ) {

            $debug_output .= 
                '<tr>' . 
                    '<td style = "vertical-align: top; whitespace: nowrap; padding-right: 10px;">' . $key . '</td>' . 
                    '<td><p">' . $value . '</p></td>' . 
                '</tr>';
            
        }

        $debug_output .= '</tbody></table>';



        // Echo debug info or return it.

        if ( $echo ) {
            
            echo $debug_output;

        } else {

            return $debug_output;

        }
    }

?>