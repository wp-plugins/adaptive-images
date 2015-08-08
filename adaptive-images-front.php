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



    // 
    // Exit, if file is accessed directly.
    // 
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
     * @return void Nothing really!
     */

    function adaptive_images_front_head_cookie_javascript () { 

        $options = get_option( 'adaptive-images' ); ?>

        <!--noptimize-->
        <script type = "text/javascript">

            // 
            // Get screen dimensions.
            // 
            <?php if ( $options['landscape'] ) : ?>
                var screen_width = Math.max( screen.width, screen.height );
            <?php else : ?>
                var screen_width = screen.width;
            <?php endif; ?>

            // 
            // Get screen device pixel ratio.
            // 
            var devicePixelRatio = window.devicePixelRatio ? window.devicePixelRatio : 1;

            // 
            // Set Adaptive Images Wordpress plugin cookie.
            // 
            document.cookie = 'resolution=' + screen_width + ',' + devicePixelRatio + '; path=/';

        </script> 
        <!--/noptimize--> <?php

    }



    /**
     * Prints out the Javascript code in the beginning of the head element, which adds a special url parameter to each
     * page's images, relevant to the device's resolution, so that CDNs can understand which cached version of an image 
     * to send to the browser.
     * 
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really!
     */

    function adaptive_images_front_head_image_cdn_javascript () { 

        $options = get_option( 'adaptive-images' ); 

        if ( ! $options['cdn-support'] ) {

            return;

        } ?>

        <!--noptimize-->
        <script type = "text/javascript">

            // 
            // When the DOM is ready.
            // 
            document.addEventListener( 'DOMContentLoaded', function ( event ) {

                var cookies = document.cookie.split( ';' );

                for ( var k in cookies ) {

                    var cookie = cookies[k].trim();

                    // 
                    // Get the plugin resolution cookie.
                    // 
                    if ( cookie.indexOf( 'resolution' ) === 0 ) {

                        // 
                        // Replace all image sources to add them the resolution cookie value.
                        // 
                        var imgs = document.querySelectorAll( 'img' );

                        for ( var k = 0; k < imgs.length; k++ ) {
                            var img = imgs[k];
                            var src = img.getAttribute( 'src' );
                            var new_src = src.indexOf( '?' ) >=0 ? src + '&' + cookie : src + '?' + cookie;
                            img.removeAttribute( 'src' );
                            img.setAttribute( 'src', new_src );
                        }

                        break;

                    }

                }

            });

        </script> 
        <!--/noptimize--> <?php

    }

?>