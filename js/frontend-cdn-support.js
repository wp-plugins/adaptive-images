/**
 * When the CDN support option is enabled the IMG elements src attibutes are replaced with data attributes in the server
 * side. Then, in the client side, as soon as the DOM is ready, the IMG src attributes are restored with their original
 * values and an added url parameter with the resolution cookie. This is necessary in cases of CDNs and caching servers
 * because, without the url parameter, they would cache only one version of each requested image.
 */

document.addEventListener( 'DOMContentLoaded', function ( event ) {

    // When the DOM is ready.

    var cookies = document.cookie.split( ';' );

    for ( var k in cookies ) {

        // Get the plugin resolution cookie.

        var cookie = cookies[k].trim();

        if ( cookie.indexOf( 'resolution' ) === 0 ) {

            // Replace all image sources to add them the resolution cookie value.

            var imgs = document.querySelectorAll( 'img[data-adaptive-images-src]' );

            for ( var k = 0; k < imgs.length; k++ ) {
                var img = imgs[k];
                var data_src = img.getAttribute( 'data-adaptive-images-src' );
                var new_src = data_src.indexOf( '?' ) >= 0 ? data_src + '&' + cookie : data_src + '?' + cookie;
                img.setAttribute( 'src', new_src );
            }

            break;

        }

    }

});