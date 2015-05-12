<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *      ALL THE ADMINISTRATIVE ACTIONS OF THE PLUGIN                                                              *
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
     * Deletes the contents of a directory recursively.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param string $dir The directory whose contents to delete recursively.
     * 
     * @return array An array with the totals of directories and files deleted so far.
     */

    function adaptive_images_actions_rmdir_recursive ( $dir ) {

        // Keep count of recursively accessed files.

        static $total_files = 0;
        static $total_dirs  = 0;
        static $total_size  = 0;



        // Do not take into acount files and symbolic links.

        if ( is_dir( $dir ) && ! is_link( $dir ) ) {

            $objects = scandir( $dir );

            foreach ( $objects as $object ) {

                if ( $object != "." && $object != ".." ) {

                    $file = $dir . "/" . $object;

                    if ( filetype( $file ) == "dir" ) { 

                        // Descend into directory children.

                        $total_dirs++;
                        adaptive_images_actions_rmdir_recursive( $file );

                    } else {

                        // Delete file.

                        $total_files++;
                        $total_size += filesize( $file );
                        unlink( $file );

                    }

                }
            }

            reset( $objects );
            rmdir( $dir );

        }



        return array( 'files' => $total_files, 'size' => $total_size, 'dirs' => $total_dirs );

    } 



    /**
     * Creates the htaccess rewrite block which ensures that iamges in watched directories are filtered by the adaptive images plugin.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param array $data The adaptive images options. If not given then the existing ones from the database will be used.
     * 
     * @return string The adaptive images plugin htaccess rewrite block.
     */

    function adaptive_images_actions_get_htaccess_block ( $data ) {

        // If no options data given then take what is in the database.

        if ( ! $data ) {

            $data = get_option( 'adaptive-images' );

        }



        // Get the document root of the server in order to help isolate the relative path of the adaptive images PHP script.

        $document_root = $_SERVER['DOCUMENT_ROOT'];

        $document_root = preg_replace( '/\//i', '\/', $document_root );
        $document_root = preg_replace( '/\./i', '\\.', $document_root );

        // Get the directory part of the request URI, if the installation is not in the server root folder.

        $request_uri = $_SERVER['REQUEST_URI'];
        $request_base_dir = substr( $request_uri, 0, strpos( $request_uri, '/wp-admin', 1 ) );



        // Get the relative path of the adaptive images PHP script

        $adaptive_images_php_script = dirname( __FILE__ );
        $adaptive_images_php_script = preg_replace( '/' . $document_root . '/i', '', $adaptive_images_php_script ); 
        $adaptive_images_php_script .= '/adaptive-images/ai-main.php';
        


        // Create the watched directories htaccess block part.

        $htaccess_rewrite_block = 
            "# BEGIN Adaptive Images\n".
            "#=======================\n" . 
            "\n" . 
            "<IfModule mod_rewrite.c>\n" . 
            "\n" . 
            "    RewriteEngine On\n" . 
            "\n" . 
            "    # Watched directories\n";

        for ( $k = 0, $length = count( $data['watched-directories'] ); $k < $length; $k++ ) {

            $watched_directory = $data['watched-directories'][$k];

            $htaccess_rewrite_block .= 
                "    RewriteCond %{REQUEST_URI} " . $request_base_dir . '/' . $watched_directory . ( $k < $length-1 ? ' [OR]' : "\n" ) . "\n";

        }



        // Create the rewrite htaccess block part.

        $htaccess_rewrite_block .= 
            "    # Redirect images through the adaptive images script\n".
            "    RewriteRule \.(?:jpe?g|gif|png)$ " . $adaptive_images_php_script . " [L]\n" . 
            "\n" . 
            "</IfModule>\n" . 
            "\n" . 
            "# END Adaptive Images";

        return $htaccess_rewrite_block;

    }



    /**
     * Updates the htacces file by adding the adaptive images rewrite block.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param array $data The adaptive images options. If not given then the existing ones from the database will be used.
     * 
     * @return boolean|WP_Error Whether the htaccess file was able to be updated or not.
     */

    function adaptive_images_actions_update_htaccess ( $data ) {

        // If no options data given then take what is in the database.

        if ( ! $data ) {

            $data = get_option( 'adaptive-images' );

        }

        // If no options in the  database then this is probably a fresh install, so stop.

        if ( ! $data ) {

            return;

        }



        // Check if htaccess is available.

        $htaccess_rewrite_block = adaptive_images_actions_get_htaccess_block( $data );

        $htaccess = adaptive_images_plugin_get_htaccess_file_path();

        $htaccess_available = adaptive_images_plugin_is_htaccess_available();



        // If htaccess available then update it with the adaprive images rewrite block.

        if ( ! $htaccess_available ) {

            return new WP_Error( 'adaptive-images-htaccess-unavailable', 'The .htaccess file could not be updated.', array( 'htaccess' => $htaccess, 'rewrite' => $htaccess_rewrite_block ) );

        } else {

            // Replace old adaptive images htaccess rewrite block with new one, or write it for the first time if it does not exist yet.

            $htaccess_old_contents = file_get_contents( $htaccess );

            $htaccess_rewrite_block_regexp = '/# BEGIN Adaptive Images.*# END Adaptive Images\n/s';
            
            if ( preg_match( $htaccess_rewrite_block_regexp, $htaccess_old_contents ) ) {

                $htaccess_new_contents = preg_replace( $htaccess_rewrite_block_regexp, $htaccess_rewrite_block . "\n", $htaccess_old_contents );

            } else {

                $htaccess_new_contents = $htaccess_rewrite_block . "\n\n" . $htaccess_old_contents;

            }



            // Write new contents of htaccess.

            $bytes = @file_put_contents( $htaccess, $htaccess_new_contents );
            
            if ( $bytes === FALSE ) {

                return new WP_Error( 'adaptive-images-htaccess-not-updated', 'The htaccess file could not be updated.', array( 'htaccess' => $htaccess, 'rewrite' => $htaccess_rewrite_block ) );

            } else {

                return FALSE;

            }

        }

    }



    /**
     * Restores the htacces file by removeing the adaptive images rewrite block.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return boolean|WP_Error Whether the htaccess file was able to be restored or not.
     */

    function adaptive_images_actions_restore_htaccess () {

        // Check if htaccess is available.

        $htaccess = adaptive_images_plugin_get_htaccess_file_path();

        $htaccess_available = adaptive_images_plugin_is_htaccess_available();



        // If htaccess available then update it with the adaprive images rewrite block.

        if ( ! $htaccess_available ) {

            return new WP_Error( 'adaptive-images-htaccess-unavailable', 'The htaccess file could not be restored.', array( 'htaccess' => $htaccess, 'rewrite' => $htaccess_rewrite_block ) );

        } else {

            $htaccess_old_contents = file_get_contents( $htaccess );
            $htaccess_new_contents = preg_replace( '/# BEGIN Adaptive Images.*# END Adaptive Images\n/s', '', $htaccess_old_contents );

            // Write new contents of htaccess.

            $bytes = @file_put_contents( $htaccess, $htaccess_new_contents );
            
            if ( $bytes === FALSE ) {

                return new WP_Error( 'adaptive-images-htaccess-not-updated', 'The htaccess file could not be updated.', array( 'htaccess' => $htaccess, 'rewrite' => $htaccess_rewrite_block ) );

            } else {

                return FALSE;

            }

        }

    }



    /**
     * Saves the adaptive images settings in the designated PHP file.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param array $data The validated data submitted in the plugin admin settings page.
     * 
     * @return boolean|WP_Error Whether the user settings file was successfully saved or not!
     */

    function adaptive_images_actions_save_user_settings ( $data ) {

        $settings_code = 
            "<?php \n" .
            "\n" .
            "    //##############################################################################################//\n" .
            "    //                                                                                              //\n" .
            "    //  DO NOT EDIT THIS FILE MANUALLY. IT IS AUTOMATICALLY GENERATED BY THE PLUGIN SETTINGS PAGE.  //\n" .
            "    //                                                                                              //\n" .
            "\n" .
            "        // Device widths resolutions. \n" .
            "\n" .
            "        \$resolutions = array( " . implode( ', ', $data['resolutions'] ) . " ); \n" .
            "\n" .
            "        // The directory of the images cache. \n" .
            "\n" .
            "        \$cache_dir = \"" . $data['cache-directory'] . "\"; \n" .
            "\n" .
            "        // JPEG quality of resized images. \n" .
            "\n" .
            "        \$jpg_quality = " . $data['jpeg-quality'] . "; \n" .
            "\n" .
            "        // Sharpen resized images. \n" .
            "\n" .
            "        \$sharpen = " . ( $data['cache-directory'] ? 'TRUE' : 'FALSE' ) . "; \n" .
            "\n" .
            "        // Check for new versions of cached images. \n" .
            "\n" .
            "        \$watch_cache = " . ( $data['watch-cache'] ? 'TRUE' : 'FALSE' ) . "; \n" .
            "\n" .
            "        // Browser cache duration for images. \n" .
            "\n" .
            "        \$browser_cache = 60 * 60 * 24 * " . $data['browser-cache'] . "; \n" .
            "\n" .
            "    //                                                                                              //\n" .
            "    //  DO NOT EDIT THIS FILE MANUALLY. IT IS AUTOMATICALLY GENERATED BY THE PLUGIN SETTINGS PAGE.  //\n" .
            "    //                                                                                              //\n" .
            "    //==============================================================================================//\n" .
            "\n" .
            "?>";



        $file = adaptive_images_plugin_get_user_settings_file_path();

        $bytes = @file_put_contents( $file, $settings_code );

        if ( $bytes === FALSE ) {

            return new WP_Error( 'adaptive-images-user-settings-not-updated', 'User settings file could not be updated.', array( 'file' => $file ) );
            
        } else {

            return TRUE;

        }
        
    }



    /**
     * Checks if the PHP GD image library is available in the server and informs the user in the admin.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_actions_check_gd_available () {

        // Do the check and inform user.

        if ( ! adaptive_images_plugin_is_gd_extension_installed() ) {

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                '<h3>Adaptive Images Error &mdash; PHP GD image library missing <hr />' . 
                '<p>The PHP GD image library, is not detected in your server.</p>' . 
                '<p>This is absolutely necessary for the plugin to function properly. Please deactivate the plugin immediately and activate it after having installed the PHP GD image library.</p>' . 
                '<p>You should probably contact your system administrator about this. You may find more information about it at the <a href = "http://php.net/manual/en/book.image.php">PHP GD image library page</a>, in the php.net website.</p>', 
                'error' 
            ); 

        }

    }



    /**
     * Checks if the PHP GD image library is available in the server and informs the user in the admin.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_actions_check_htaccess_ok () {

        // If options have not been saved yet then do not check.

        $options = get_option( 'adaptive-images' );

        if ( ! $options ) {

            return;
            
        }



        // Do the check and inform user.

        $htaccess = adaptive_images_plugin_get_htaccess_file_path();
        $htaccess_available = adaptive_images_plugin_is_htaccess_available();

        $htaccess_contents = file_get_contents( $htaccess );
        $htaccess_rewrite_block_regexp = '/# BEGIN Adaptive Images.*# END Adaptive Images\n/s';
        $htaccess_contents_updated = preg_match( $htaccess_rewrite_block_regexp, $htaccess_contents );

        if ( ! $htaccess_available || ! $htaccess_contents_updated ) {

            $permissions = adaptive_images_plugin_file_permissions( $htaccess );

            $message = 
                'Adaptive Images Error &mdash; Htaccess file not updated <hr />' . 
                '<p>' . 
                    'The Adaptive Images settings are saved, but the .htaccess file was not able to be updated yet.' .
                '</p>' . 
                '<p>' . '
                    Please try to save the plugin settings once again in <a href = "options-general.php?page=adaptive-images">Adaptive Images Settings</a>. If the problem persists, then you should contact your system administrator and inform them about the issue.' . 
                '</p>' . 
                '<p>' . 
                    'The .htaccess file permissions are: <code>' . $htaccess . ' => ' . $permissions . '</code>.' . 
                '</p>';

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                $message, 
                'error' 
            ); 

        }

    }



    /**
     * Checks if the plugin settings have been saved for the first time adn inform the user accirdingly.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_actions_check_settings_saved () {

        // If options have not been saved yet then do not check.

        if ( ! adaptive_images_plugin_are_options_set() ) {

            add_settings_error( 
                'adaptive-images-settings', 
                'adaptive-images-settings-error', 
                'Adaptive Images Error &mdash; Settings not saved <hr />' . 
                '<p>The Adaptive Images settings have not been saved yet.</p>' . 
                '<p>The plugin is active but its settings have not been initialized, so the plugin is not actually functioning yet.</p>' . 
                '<p>Nothing to worry about, just go to <a href = "options-general.php?page=adaptive-images">Adaptive Images Settings</a> page in order to save your configuration and start to actually use the plugin.</p>', 
                'error' 
            ); 

        }

    }

?>