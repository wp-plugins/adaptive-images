<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *      ALL FUNCTIONS THAT REFER TO THE USER FRONTEND/THEME                                                       *
     *      ===================================================                                                       *
     *                                                                                                                *
     *      Nevma (info@nevma.gr)                                                                                     *
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
     * @return Nothing really!
     */

    function adaptive_images_front_head_cookie_javascript () { ?>

        <script type = "text/javascript">

            document.cookie = 'resolution=' + Math.max( screen.width, screen.height ) + '; path=/';

        </script> <?php

    }

?>