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
        <script src = "<?php echo adaptive_images_plugin_get_url(); ?>/js/frontend-cdn-support.js" type = "text/javascript"></script>
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
    
    function adaptive_images_front_replace_img_src ( $matches ) {
    
    	return $matches[1] . 'data-adaptive-images-src' . $matches[2] . '<noscript>' . $matches[0] . '</noscript>';
    
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

        /**
         * Regular expression test: http://regexr.com/3c7f8
         * 
         * RICG
         * ====
         * 
         * https://responsiveimages.org/
         * 
         * <img src="small.jpg"
         *      srcset="large.jpg 1024w, medium.jpg 640w, small.jpg 320w"
         *      sizes="(min-width: 36em) 33.3vw, 100vw"
         *      alt="A rad wolf">
         * 
         * <picture>
         *   <source media="(min-width: 40em)" srcset="big.jpg 1x, big-hd.jpg 2x">
         *   <source srcset="small.jpg 1x, small-hd.jpg 2x">
         *   <img src="fallback.jpg" alt="">
         * </picture>
         * 
         * OTHER
         * =====
         * 
         * <picture>
         *   <source srcset="mdn-logo-wide.png" media="(min-width: 600px)">
         *   <source srcset="mdn-logo.svg" type="image/svg+xml">
         *   <img src="mdn-logo-narrow.png" alt="MDN">
         * </picture>
         * 
         * <figure>
         *   <img src="https://developer.cdn.mozilla.net/media/img/mdn-logo-sm.png" alt="An awesome picture">
         *   <figcaption>Fig1. MDN Logo</figcaption>
         * </figure>
         */

    	$image_src_reg_exp = '/(<img\s+(?:[^\s>\"\'=]*\s*=\s*\"[^\"]*\"\s+)*)src(\s*=\s*\"[^\"]*\"(?:\s+[^\s>\"\'=]*\s*=\s*\"[^\"]*\")*\s*\/?>)/im';
    
    	return preg_replace_callback( $image_src_reg_exp, 'adaptive_images_front_replace_img_src', $buffer );
    
    }

?>