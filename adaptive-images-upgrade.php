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
     * Checks if the plugin has actually been upgraded and acts accordingly for each version.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_upgrade_action_upgraded () {
    	
        $options = adaptive_images_plugin_get_options();



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



        // General plugin updated message.

        add_action( 'admin_notices', 'adaptive_images_upgrade_message_upgraded' );

        

        // Check if upgrading from version 0.2.08 which needs some special handling.

        if ( $previous_version == '0.2.08' ) {
            
            adaptive_images_upgrade_action_upgraded_from_v0208();

            add_action( 'admin_notices', 'adaptive_images_upgrade_message_upgraded_from_v0208' );

        }



        // Check if upgrading to version 0.5.0 to show a nice informative message.

        if ( $current_version == '0.5.0' ) {

            add_action( 'admin_notices', 'adaptive_images_upgrade_message_upgraded_to_v050' );
            
        }



        // Check if upgrading to version 0.7.0 to show a nice informative message.

        if ( $current_version == '0.7.0' ) {
        	
	        // Delete old sanitized option.
	        
	        unset( $options['sanitized'] );

            add_action( 'admin_notices', 'adaptive_images_upgrade_message_upgraded_to_v070' );
            
        }



        // Save current version in plugin settings.
        
        $options['version'] = $current_version;
        
        
        update_option( 'adaptive-images', $options );
        
    }



    /**
     * Performs actions related to upgrading from version 0.2.08 because from that version and on major code and 
     * settings changes took place.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_upgrade_action_upgraded_from_v0208 () {

        // Try to remove old htaccess entry.

        $htaccess = adaptive_images_plugin_get_htaccess_file_path();

        $htaccess_available = adaptive_images_debug_is_htaccess_writeable();

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



    /**
     * Adds the admin notice message that informs the user when the plugin has been generally upgraded.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_upgrade_message_upgraded () {

        $current_version = adaptive_images_plugin_get_version();

        echo 
            '<div class = "updated settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    '<strong>Adaptive Images</strong>' . 
                '</p>' . 
                '<hr />' . 
                '<p>' . 
                    'The Adaptive Images plugin has been succesfully upgraded to version ' . $current_version . '. Perhaps you would like to review its <a href = "options-general.php?page=adaptive-images">Settings</a>.' .
                '</p>' . 
            '</div>';

    }



    /**
     * Adds the admin notice message that informs the user when the pluggin has been upgraded from version 0.2.08, 
     * where major code and settings changes took place and the user settings need to be manually updated. 
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_upgrade_message_upgraded_from_v0208 () {

        $current_version = adaptive_images_plugin_get_version();

        echo 
            '<div class = "updated settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    '<strong>Adaptive Images</strong>' . 
                '</p>' . 
                '<hr />' . 
                '<p>' . 
                    'The Adaptive Images plugin has been recently upgraded to version ' . $current_version . '.' .
                '</p>' . 
                '<p>' .
                    'You should probably save your settings once again in <a href = "options-general.php?page=adaptive-images">Adaptive Images Settings</a>, because since version 0.2.08 major rewrites of the code have taken place.' .
                '</p>' .
                '<p>' .
                    'We are very sorry for the inconvenience and we promise to keep a steady path from now on.'. 
                '</p>' . 
            '</div>';

    }



    /**
     * Adds the admin notice message that informs the user when the pluggin has been upgraded to version 0.5.0.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_upgrade_message_upgraded_to_v050 () {

        $current_version = adaptive_images_plugin_get_version();

        echo 
            '<div class = "updated settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    '<strong>Adaptive Images</strong>' .
                '</p>' . 
                '<hr />' . 
                '<p>' . 
                    'The plugin has been upgraded to version ' . $current_version . '. Version ' . $current_version . ' has some very interesting new features. Go to <a href = "options-general.php?page=adaptive-images">Adaptive Images Settings</a> to check them out.' .
                '</p>' . 
            '</div>';

    }



    /**
     * Adds the admin notice message that informs the user when the pluggin has been upgraded to version 0.7.0.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void
     */

    function adaptive_images_upgrade_message_upgraded_to_v070 () {

        echo 
            '<div class = "updated settings-error notice is-dismissible adaptive-images-settings-error">' .
                '<p>' . 
                    '<strong>Adaptive Images</strong>' . 
                '</p>' . 
                '<hr />' . 
                '<em>(A message from the developers)</em>' . 
                '<div style = "padding: 20px; border: 1px solid #b4b9be; background: #f7f7f7; border-radius: 5px; margin: 10px 0 15px 0;">' .
                    '<p>' . 
                        'Hey there!' .
                    '</p>' . 
                    '<p>' . 
                        'Just wanted to say a big thank you to all of you out there for trying out our plugin in these early versions. We struggle hard to keep it solid and reach a full stable version! You have probably noticed, or will notice in a minute, that a number of enhancements and user interface improvements have been added in this version (0.7.0). The reason the last versions came up so close to one another was that -well- it was August and we had some extra spare time!' . 
                    '</p>' . 
                    '<p>' . 
                        'As we are reaching version 1.0, the plugin features are becoming more and more stable and -we believe- more and more powerful. One of the most interesting, yet still a tiny bit experimental, features we have added is support for CDN and other external caching solutions, like our beloved Varnish. Once we have reached this milestone, a major step forward will have been accomplished. We really appreciate your feedback on this.' . 
                    '</p>' . 
                    '<p>' . 
                        'Once again, thank you so much and stay tuned!' . 
                    '</p>' . 
                    '<p>' . 
                        ':-)' . 
                    '</p>' . 
                    '<p>' . 
                        '<a href = "http://www.nevma.gr" target = "_blank" style = "text-decoration: none;">Nevma</a> <br />' . 
                        '(the development team)' .
                    '</p>' . 
                '</div>' . 
            '</div>';

    }

?>