<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *                                                                                                                *
     *      PLUGIN UPGRADE FUNCTIONS                                                                                  *
     *      ========================                                                                                  *
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
     * Checks if the plugin has been upgraded and acts accordingly for each version.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_upgrade_plugin_upgraded () {

        $options = get_option( 'adaptive-images' );

        // Perhaps the previous version was 0.2.08 or earlier, where the options were a bit of a mess.

        if ( ! $options && get_option( 'wprxr_include_paths', FALSE ) !== FALSE ) {

            // Fake the options array of version 0.2.08.

        	$options = array( 'version' => '0.2.08' );

        }

        // If actually a fresh install, then nothing more is necessary.
        
        if ( ! $options ) {

        	return;

        }



        // Check if we have just upgraded from a lower version.

        $previous_version = $options['version'];
        $current_version  = adaptive_images_plugin_get_version();
        $plugin_upgraded  = strcmp( $previous_version, $current_version ) < 0; 

        if ( ! $plugin_upgraded ) {

            return;

        }



        // Stuff necessary for upgrading to plugin version 0.3.0 from previous version 0.2.08.

        if ( $previous_version == '0.2.08' ) {
            
            adaptive_images_upgrade_plugin_upgraded_to_v030();

            add_action( 'admin_notices', 'adaptive_images_upgrade_plugin_upgraded_to_v030_message' );

        } else {

            // We probably do not need this message but let's keep just in case.

            add_action( 'admin_notices', 'adaptive_images_upgrade_plugin_upgraded_message' );

            $options['version'] = $current_version;
            update_option( 'adaptive-images', $options );
            
        }

    }



    /**
     * Adds the admin notice message that informs the user when the pluggin has been upgraded to version 0.3.0, which 
     * has a major rewrite and the user settings needed to be manually updated. It is done via an  admin notice and 
     * not via the settings errors, because in some pages the settings errors are called by the system itself and this
     * results in being called multiple times.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_upgrade_plugin_upgraded_to_v030_message () {

        $current_version = adaptive_images_plugin_get_version();

        echo 
            '<div class = "updated settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    'Adaptive Images &mdash; Upgraded' . 
                '</p>' . 
                '<hr />' . 
                '<p>' . 
                    'The Adaptive Images plugin has been recently updated to version ' . $current_version . '.' .
                '</p>' . 
                '<p>' .
                    'You should probably save your settings once again in <a href = "options-general.php?page=adaptive-images">Adaptive Images Settings</a>, because version 0.3.0 was a major rewrite of the code.'  .
                '</p>' .
                '<p>' .
                    'We are very sorry for the inconvenience and we promise to keep a steady path from now on.'. 
                '</p>' . 
            '</div>';

    }



    /**
     * Adds the admin notice message that informs the user when the pluggin has been generally upgraded. It is done 
     * via an  admin notice and not via the settings errors, because in some pages the settings errors are called by
     * the system itself and this results in being called multiple times.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_upgrade_plugin_upgraded_message () {

        $current_version = adaptive_images_plugin_get_version();

        echo 
            '<div class = "updated settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    'Adaptive Images &mdash; Upgraded' . 
                '</p>' . 
                '<hr />' . 
                '<p>' . 
                    'The Adaptive Images plugin has been succesfully updated to version ' . $current_version . '.' .
                '</p>' . 
            '</div>';

    }



    /**
     * Checks if the plugin has been upgraded and acts accordingly for each version.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_upgrade_plugin_upgraded_to_v030 () {

	    // Try to remove old htaccess entry.

	    $htaccess = adaptive_images_plugin_get_htaccess_file_path();

	    $htaccess_available = adaptive_images_plugin_is_htaccess_writeable();

	    if ( $htaccess_available ) {

	        $htaccess_old_contents = file_get_contents( $htaccess );
	        $htaccess_new_contents = preg_replace( '/# Adaptive Images.*# END Adaptive Images\n/s', '', $htaccess_old_contents );

	        @file_put_contents( $htaccess, $htaccess_new_contents );

	    }



	    // Try to remove old cache directory.

	    $old_cache_path = realpath( dirname( $_SERVER['SCRIPT_FILENAME'] ) . '/../wp-content/' ) . '/cache-ai/';

	    adaptive_images_actions_rmdir_recursive( $old_cache_path );



	    // Try to remove old options.

	    delete_option( 'wprxr_include_paths' );
	    delete_option( 'wprxr_ai_config' );

    }

?>