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
     * Prints the Javascript code in the beginning of the head element, which sets the cookie with the user device width.
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