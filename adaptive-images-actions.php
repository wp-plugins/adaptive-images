<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *                                                                                                                *
     *      ALL THE ADMINISTRATIVE ACTIONS OF THE PLUGIN                                                              *
     *      ============================================                                                              *
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
     * Deletes the contents of a directory recursively.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param string $dir The directory whose contents to delete recursively.
     * 
     * @return array An array with the totals of directories and files deleted so far.
     */

    function adaptive_images_actions_rmdir_recursive ( $dir ) {

        //
        //  USE BASH COMMANDS TEST
        //  

        // var_dump( $dir );
        // echo '<hr />';

        // $result = exec( 'rm -rfv ' . $dir, $output, $code );

        // var_dump( $result ); // ==> empty when not done, echoes "removed directory: `/foo/bar'" when done
        // echo '<hr />';
        // var_dump( $output ); // ==> empty when not done, holds lines of command output when done
        // echo '<hr />';
        // var_dump( $code );   // ==> 0 when command executed successfully, 1 when not
        // echo '<hr />';

        // return;



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
     * Creates the htaccess rewrite block which ensures that images in watched directories are filtered by the 
     * adaptive images plugin.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param array $options The adaptive images options. If not given then the existing ones from the database will be 
     *                    used.
     * 
     * @return string The adaptive images plugin htaccess rewrite block.
     */

    function adaptive_images_actions_htaccess_get_rewrite_block ( $options ) {

        // If no options data given then take what is in the database.

        if ( ! $options ) {

            $options = adaptive_images_plugin_get_options();

        }



        // Get the directory part of the request, if we are not in the virtual host root directory.

        $request_uri      = $_SERVER['REQUEST_URI'];
        $request_uri_base = substr( $request_uri, 0, strpos( $request_uri, '/wp-admin', 1 ) );

        // Isolate the relative path of the adaptive images PHP script inside the WordPress installation directory.
        
        $wp_home_path = get_home_path();

        $wp_home_path = preg_replace( '/\//i', '\/', $wp_home_path );
        $wp_home_path = preg_replace( '/\./i', '\\.', $wp_home_path );
        
        $adaptive_images_dir_path          = dirname( __FILE__ );
        $adaptive_images_dir_path_relative = preg_replace( '/' . $wp_home_path . '/i', '', $adaptive_images_dir_path );

        $adaptive_images_php_script = $request_uri_base. '/' . $adaptive_images_dir_path_relative . '/adaptive-images-script.php';

        // If no starting slash then add it.

        if ( strpos( $adaptive_images_php_script, '/' ) !== 0 ) {

            $adaptive_images_php_script = '/' . $adaptive_images_php_script;

        }
        


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

        for ( $k = 0, $length = count( $options['watched-directories'] ); $k < $length; $k++ ) {

            $watched_directory = $options['watched-directories'][$k];

            $htaccess_rewrite_block .= 
                "    RewriteCond %{REQUEST_URI} " . $request_uri_base . '/' . $watched_directory . ( $k < $length-1 ? ' [OR]' : "\n" ) . "\n";

        }
        
        
        
        // Image content types/extensions to handle.
        
        $content_types = implode( '|', $options['content-types']);



        // Create the rewrite htaccess block part.

        $htaccess_rewrite_block .= 
            "    # Redirect images through the adaptive images script\n".
            "    RewriteRule \.(?:" . $content_types . ")$ " . $adaptive_images_php_script . " [L]\n" . 
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
     * @param array $options The adaptive images options. If not given then the existing ones from the database will be used.
     * 
     * @return boolean|WP_Error Whether the htaccess file was able to be updated or not.
     */

    function adaptive_images_actions_htaccess_update ( $options ) {

        // If no options data given then take what is in the database.
        
        if ( ! $options ) {

            $options = adaptive_images_plugin_get_options();

        }

        // If no options in the  database then this is probably a fresh install, so stop.

        if ( ! $options ) {

            return;

        }



        // Check if htaccess is available.

        $htaccess_rewrite_block = adaptive_images_actions_htaccess_get_rewrite_block( $options );

        $htaccess = adaptive_images_plugin_get_htaccess_file_path();

        $htaccess_writeable = adaptive_images_debug_is_htaccess_writeable();



        // If htaccess available then update it with the Adaptive Images rewrite block.

        if ( ! $htaccess_writeable ) {

            return new WP_Error( 'adaptive-images-htaccess-unavailable', 'The htaccess file could not be updated.', array( 'htaccess' => $htaccess, 'rewrite' => $htaccess_rewrite_block ) );

        }

        
        
        // Replace old adaptive images htaccess rewrite block with new one, or write it for the first time if it does not exist yet.

        $htaccess_old_contents = file_get_contents( $htaccess );

        $htaccess_rewrite_block_regexp = '/# BEGIN Adaptive Images.*# END Adaptive Images\n/s';
            
        if ( preg_match( $htaccess_rewrite_block_regexp, $htaccess_old_contents ) ) {

            $htaccess_new_contents = preg_replace( $htaccess_rewrite_block_regexp, $htaccess_rewrite_block . "\n", $htaccess_old_contents );

        } else {

            $htaccess_new_contents = $htaccess_rewrite_block . "\n\n" . $htaccess_old_contents;

        }
        
        
        
        // Backup old htaccess file.
        
        copy( $htaccess, $htaccess . '.ai.bak' );



        // Write new contents of htaccess.

        $bytes = file_put_contents( $htaccess, $htaccess_new_contents );
        
        if ( $bytes === FALSE ) {

            return new WP_Error( 'adaptive-images-htaccess-not-updated', 'The htaccess file could not be updated.', array( 'htaccess' => $htaccess, 'rewrite' => $htaccess_rewrite_block ) );

        } else {

        	return TRUE;

        }

    }



    /**
     * Restores the htacces file by removing the adaptive images rewrite block.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return boolean|WP_Error Whether the htaccess file was able to be restored or not.
     */

    function adaptive_images_actions_htaccess_restore () {

        // Check if htaccess is available.

        $htaccess = adaptive_images_plugin_get_htaccess_file_path();

        $htaccess_writeable = adaptive_images_debug_is_htaccess_writeable();



        // If htaccess available then update it with the adaprive images rewrite block.

        if ( ! $htaccess_writeable ) {

            return new WP_Error( 'adaptive-images-htaccess-unavailable', 'The htaccess file could not be restored.', array( 'htaccess' => $htaccess, 'rewrite' => $htaccess_rewrite_block ) );

        } else {

            $htaccess_old_contents = file_get_contents( $htaccess );
            $htaccess_new_contents = preg_replace( '/# BEGIN Adaptive Images.*# END Adaptive Images\n/s', '', $htaccess_old_contents );

            // Write new contents of htaccess.

            $bytes = @file_put_contents( $htaccess, $htaccess_new_contents );
            
            if ( $bytes === FALSE ) {

                return new WP_Error( 'adaptive-images-htaccess-not-updated', 'The htaccess file could not be updated.', array( 'htaccess' => $htaccess, 'rewrite' => $htaccess_rewrite_block ) );

            } else {

                return TRUE;

            }

        }

    }



    /**
     * Saves the adaptive images settings in the designated PHP file.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @param array $options The validated data submitted in the plugin admin settings page.
     * 
     * @return boolean|WP_Error Whether the user settings file was successfully saved or not!
     */

    function adaptive_images_actions_user_settings_php_save ( $options ) {

        // If no options data given then take what is in the database.

        if ( ! $options ) {

            $options = adaptive_images_plugin_get_options();

        }

        // If no options in the  database then this is probably a fresh install, so stop.

        if ( ! $options ) {

            return new WP_Error( 'adaptive-images-user-settings-not-updated', 'User settings were given empty.', array( 'file' => $file ) );

        }



        // The PHP code of the user settings.
        
        $settings_code = 
            "<?php \n" .
            "\n" .
            "    //##############################################################################################\\\\\n" .
            "    //                                                                                              \\\\\n" .
            "    //                                 ADAPTIVE IMAGES USER SETTINGS                                \\\\\n" .
            "    //                                ===============================                               \\\\\n" .
            "    //                                                                                              \\\\\n" .
            "    //  DO NOT EDIT THIS FILE MANUALLY. IT IS AUTOMATICALLY GENERATED BY THE PLUGIN SETTINGS PAGE.  \\\\\n" .
            "    //                                                                                              \\\\\n" .
            "\n" .
            "        // Device width resolutions. \n" .
            "        \n" .
            "        \$resolutions = array( " . implode( ', ', $options['resolutions'] ) . " ); \n" .
            "\n" .
            "        // Whether to take the landscape width into account or not. \n" .
            "        \n" .
            "        \$landscape = " . ( $options['landscape'] ? 'TRUE' : 'FALSE' ) . "; \n" .
            "\n" .
            "        // Whether to show higher resolution images to HiDPI screens or not. \n" .
            "        \n" .
            "        \$hidpi = " . ( $options['hidpi'] ? 'TRUE' : 'FALSE' ) . "; \n" .
            "\n" .
            "        // The directory of the images cache. \n" .
            "        \n" .
            "        \$cache_dir = \"" . $options['cache-directory'] . "\"; \n" .
            "\n" .
            "        // JPEG quality of resized images. \n" .
            "        \n" .
            "        \$jpg_quality = " . $options['jpeg-quality'] . "; \n" .
            "\n" .
            "        // Sharpen resized images. \n" .
            "        \n" .
            "        \$sharpen = " . ( $options['sharpen-images'] ? 'TRUE' : 'FALSE' ) . "; \n" .
            "\n" .
            "        // Check for new versions of cached images. \n" .
            "        \n" .
            "        \$watch_cache = " . ( $options['watch-cache'] ? 'TRUE' : 'FALSE' ) . "; \n" .
            "\n" .
            "        // Browser cache duration for images. \n" .
            "        \n" .
            "        \$browser_cache = 60 * 60 * 24 * " . $options['browser-cache'] . "; \n" .
            "\n" .
            "    //                                                                                              \\\\\n" .
            "    //  DO NOT EDIT THIS FILE MANUALLY. IT IS AUTOMATICALLY GENERATED BY THE PLUGIN SETTINGS PAGE.  \\\\\n" .
            "    //                                                                                              \\\\\n" .
            "    //==============================================================================================\\\\\n" .
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

?>