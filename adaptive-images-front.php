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
     * @return void Nothing really!
     */

    function adaptive_images_front_head_cookie_javascript () { 

        $options = adaptive_images_plugin_get_options(); ?>

        <!--noptimize-->
        <?php if ( $options['landscape'] ) : ?>
        
            <script src = "<?php echo adaptive_images_plugin_get_url(); ?>/js/frontend-cookie-landscape.js" type = "text/javascript"></script>
        
        <?php else : ?>
        
            <script src = "<?php echo adaptive_images_plugin_get_url(); ?>/js/frontend-cookie-portrait.js" type = "text/javascript"></script>
        
        <?php endif; ?>
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

        $options = adaptive_images_plugin_get_options(); 

        if ( ! $options['cdn-support'] ) {

            return;

        } ?>

        <!--noptimize-->
        <script type = "text/javascript">

            //
            // THE SERVER SIDE IMG SRC REPLACEMENT SOLUTION (1)
            // 
            
            // When the DOM is ready.

            document.addEventListener( 'DOMContentLoaded', function ( event ) {

                var cookies = document.cookie.split( ';' );

                for ( var k in cookies ) {

                    var cookie = cookies[k].trim();

                    // Get the plugin resolution cookie.

                    if ( cookie.indexOf( 'resolution' ) === 0 ) {

                        // Replace all image sources to add them the resolution cookie value.

                        var imgs = document.querySelectorAll( 'img[data-adaptive-images-src]' );

                        for ( var k = 0; k < imgs.length; k++ ) {
                            var img = imgs[k];
                            var data_src = img.getAttribute( 'data-adaptive-images-src' );
                            var new_src = data_src.indexOf( '?' ) >=0 ? data_src + '&' + cookie : data_src + '?' + cookie;
                            img.setAttribute( 'src', new_src );
                        }

                        break;

                    }

                }

            });
            
			/*********************************************************************************************************/
            
            // 
            // THE PURE JAVASCRIPT SOLUTION (2)
            // 
            
            // var resolution = null;

            // var cookies = document.cookie.split( ';' );

            // for ( var k in cookies ) {

            //     var cookie = cookies[k].trim();

            //     if ( cookie.indexOf( 'resolution' ) === 0 ) {

            //         resolution = cookie;

            //     }

            // }

            // function handle_images () {

            //     var imgs = document.querySelectorAll( 'img' );

            //     for ( var k = 0; k < imgs.length; k++ ) {

            //         var img = imgs[k];

            //         if ( img.complete || img.getAttribute( 'data-adaptive-images' ) ) {

            //             continue;

            //         }

            //         var src = img.getAttribute( 'src' );
            //         var new_src = src.indexOf( '?' ) >=0 ? src + '&' + resolution : src + '?' + resolution;

            //         img.removeAttribute( 'src' );
            //         img.setAttribute( 'src', new_src );
            //         img.setAttribute( 'data-adaptive-images', true );

            //     }

            // }

            // var handler = window.setInterval( handle_images, 10 );

            // document.addEventListener( 'DOMContentLoaded', function ( event ) {

            //     window.clearInterval( handler );
            //     handle_images();

            // });

        </script> 
        <!--/noptimize--> <?php

    }
    
    
    
    /**
     * Begins capturing the HTML output to the browser in the buffer and registers the functin that will handle it in  
     * the end. That function will be used to appropriately handle IMG elements so that the corresponding Javascript 
     * in the browser will make it load via the CDN with the resolution cookie details as a url parameter. 
     *
     * @author Nevma (info@nevma.gr)
     * 
     * @return void Nothing really.
     */
    
    function adaptive_images_front_start_buffering_for_cdn () {
    	
    	ob_start( 'adaptive_images_front_replace_image_sources_from_html' );
    
    }
    
    
    
    /**
     * Ends capturing the HTML output to the browser.
     *
     * @author Nevma (info@nevma.gr)
     *
     * @return void Nothing really.
     */
    
    function adaptive_images_front_end_buffering_for_cdn () {
    	
    	if ( ob_get_level() > 0 ) {
    
	    	ob_flush();
	    	
    	}
    
    }
    
    
    
    /**
     * Function called via a preg_replace_callback() in order to handle matched part of a regular expression.
     *
     * @author Nevma (info@nevma.gr)
     * 
     * @param $matches array The data of the current match of the regular expression.
     *
     * @return void Nothing really.
     */
    
    function adaptive_images_front_replace_image_source ( $matches ) {
    
    	return 'data-adaptive-images-src="' . $matches[1] . '"';
    
    }
    
    
    
    /**
     * Replaces HTML image src attributes with a special data attribute which will be handled by Javascript on the 
     * browser side and be replaced there with the appropriate source according to the device dimensions.
     *
     * @author Nevma (info@nevma.gr)
     *
     * @param $buffer string The HTML source that is to be sent to the browser.
     * 
     * @return string The HTML source to be sent to the browser where the image src attributes have been replaced 
     * 				  with appropriate data attributes.
     */
    
    function adaptive_images_front_replace_image_sources_from_html ( $buffer ) {
    
    	$image_src_reg_exp = '/<img.+src=\s*"(.[^"]+)"/i';
    
    	return preg_replace_callback( $image_src_reg_exp, 'adaptive_images_front_replace_image_source', $buffer );
    
    }

?>