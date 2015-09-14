jQuery( function () {

    // Confirmation thickbox button handler.
    
    jQuery( '.tb-confirm' ).on( 'click', function () {

        // Does not return false.
        
    	tb_remove();

    });


    
    // Close/cancel thickbox button handler.

    jQuery( '.tb-remove' ).on( 'click', function () {

        // Returns false indeed.
        
    	tb_remove();
        return false;

    });
    
    

    // Open close settings help panel.

    jQuery( '.adaptive-images-help-button' ).toggle(
        function () {

            jQuery( this ).
                parent().
                find( '.adaptive-images-help-content' ).
                slideDown({ duration: 250 });
            return false;

        },
        function () {
            
            jQuery( this ).
                parent().
                find( '.adaptive-images-help-content' ).
                slideUp({ duration: 250 });
            return false;

        }
    );

});