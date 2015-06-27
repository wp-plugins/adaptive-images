<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *                                                                                                                *
     *      ALL FUNCTIONS THAT REFER TO THE USER FRONTEND/THEME                                                       *
     *      ===================================================                                                       *
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
     * Prints out the Javascript code in the beginning of the head element, which sets the cookie with the user device 
     * width/resolution. This cookie is necessary from there and on so that the plugin can know which image sizes to 
     * serve to this client.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return voidNothing really!
     */

    function adaptive_images_front_head_cookie_javascript () { 

        $options = get_option( 'adaptive-images' ); ?>

        <!--noptimize-->
        <script type = "text/javascript">

            // Set Adaptive Images Wordpress plugin cookie.
            
            <?php if ( $options['landscape'] ) : ?>

                var screen_width = Math.max( screen.width, screen.height );

            <?php else : ?>

                var screen_width = screen.width;

            <?php endif; ?>

            var devicePixelRatio = window.devicePixelRatio ? window.devicePixelRatio : 1;

            document.cookie = 'resolution=' + screen_width + ',' + devicePixelRatio + '; path=/';

        </script> 
        <!--/noptimize--> <?php

    }

?>