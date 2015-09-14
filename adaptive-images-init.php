<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *                                                                                                                *
     *      THIS FILE HOLDS ALL THE INCLUDES, ACTIONS AND FILTERS WHICH ARE REQUIRED                                  *
     *      ========================================================================                                  *
     *                                                                                                                *
     *      Nevma (info@nevma.gr)                                                                                     *
     *                                                                                                                *
     *                                                                                                                *
     ******************************************************************************************************************/



    // Exit, if file is accessed directly.

    if ( ! defined( 'ABSPATH' ) ) {

        exit; 

    }



    // Include the plugin PHP files in the correct order.

    require_once( 'adaptive-images-plugin.php' );
    require_once( 'adaptive-images-actions.php' );
    require_once( 'adaptive-images-upgrade.php' );
    require_once( 'adaptive-images-admin.php' );
    require_once( 'adaptive-images-front.php' );
    require_once( 'adaptive-images-debug.php' );



    // Plugin codename for filters and hooks
    
    $plugin_name = adaptive_images_plugin_get_name();    

    // Get plugin options. 
    
    $options = adaptive_images_plugin_get_options();



    // Admin area hooks and filters.

    if ( is_admin() ) {

        // Update installation htaccess when plugin is activated.
        
        register_activation_hook( $plugin_name, 'adaptive_images_actions_htaccess_update'  );



        // Restore installation htaccess when plugin is deactivated.

        register_deactivation_hook( $plugin_name, 'adaptive_images_actions_htaccess_restore' );
    


        // Adds the actions which register the plugin settings.

        add_action( 'admin_init', 'adaptive_images_admin_settings_register' );
        add_action( 'admin_menu', 'adaptive_images_admin_settings_page_add' );
        add_filter( 'plugin_action_links_' . $plugin_name, 'adaptive_images_admin_settings_link_add' ); 
        add_filter( 'plugin_row_meta', 'adaptive_images_admin_settings_row_meta_add', 10, 2 );

        
        
        // Adds the actions which print the plugin admin CSS and JS.

        add_action( 'admin_head', 'adaptive_images_admin_css' );
        add_action( 'admin_head', 'adaptive_images_admin_js' );

        

        // Adds the action which checks for possible plugin upgrades.

        add_action( 'admin_head', 'adaptive_images_upgrade_action_upgraded' );



        // Adds the actions which check if plugin is set up OK.

        add_action( 'admin_head', 'adaptive_images_debug_check_gd_available_init' );
        add_action( 'admin_head', 'adaptive_images_debug_check_settings_saved_init' );
        add_action( 'admin_head', 'adaptive_images_debug_check_htaccess_updated_init' );
        add_action( 'admin_head', 'adaptive_images_debug_check_cache_available_init' );

    }



    // Theme frontend hooks and filters.
    
    if ( ! is_admin() ) {
        
        // Sets up the cookie generating Javascript in the head element of the theme.

        add_action( 'wp_head', 'adaptive_images_front_head_cookie_javascript', 0 );

        // Sets up the Javascript which adds a URL parameter to images for CDNs in the head element of the theme.

        add_action( 'wp_head', 'adaptive_images_front_head_image_cdn_javascript', 0 );

        
       
        // Adds the actions that filter HTML output for image sources and CDN support.

        if ( $options['cdn-support'] ) {

            add_action( 'init', 'adaptive_images_front_start_buffering_for_cdn', 0 );
            add_action( 'shutdown', 'adaptive_images_front_end_buffering_for_cdn',   9999 );
            
        }

    }
    
?>