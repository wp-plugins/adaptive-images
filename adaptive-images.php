<?php

    /******************************************************************************************************************
     *                                                                                                                *
     *                                                                                                                *
     *      THIS FILE IS THE MAIN PLUGIN FILE                                                                         *
     *      =================================                                                                         *
     *                                                                                                                *
     *      Nevma (info@nevma.gr)                                                                                     *
     *                                                                                                                *
     *                                                                                                                *
     *****************************************************************************************************************/



    /*
       Plugin Name: Adaptive Images for WordPress 
       Plugin URI: http://www.nevma.gr
       Description: Resizes your images, by device screen size, to reduce download time in the mobile web.
       Version: 0.3.04
       Author: Nevma - Creative Know-How
       Author URI: http://www.nevma.gr
       License: GPL2
       License URI: https://www.gnu.org/licenses/gpl-2.0.html
    */



    /**
     * Copyright 2015 Nevma (nevma.gr, info@nevma.gr)
     *
     * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General 
     * Public License, version 2, as published by the Free Software Foundation. This program is distributed in the hope
     * that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
     * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License along with this program; if not, write to 
     * the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA.
     * 
     * GPL: http://www.gnu.org/copyleft/gpl.html
     * 
     * Uses Adaptive Images, version 1.5.2, http://adaptive-images.com, https://github.com/MattWilcox/Adaptive-Images, 
     * by Matt Wilcox, which is licensed under a Creative Commons Attribution 3.0 Unported License.
     */


    
    // Exit, if file is accessed directly.

    if ( ! defined( 'ABSPATH' ) ) {

        exit; 

    }



    // Setup plugin, includes, actions and filters.
    
    require_once( 'adaptive-images-init.php' );

?>