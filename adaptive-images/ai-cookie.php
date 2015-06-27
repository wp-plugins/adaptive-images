<?php

    // Manually set the cookie to a value.

    $resolution = isset( $_GET['resolution'] ) ? intval( $_GET['resolution'] ) : null;

    if ( $resolution === NULL || $resolution === "unknown" ) { 

        // We need a number, so give it something unfeasible.

        $resolution = 3000; 

    } 



    // Set the cookie.

    setcookie( 'resolution', $resolution, time() + 604800, '/' ); 



    // Respond with an empty content.

    header( 'HTTP/1.1 200 OK' );
    header( 'Content-Type: text/plain' );
    echo 'Cookie set at resolution: ' . $resolution . '.';
    exit();

?>