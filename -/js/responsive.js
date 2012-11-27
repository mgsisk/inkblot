/** Responsive menu script.
 * 
 * @package Inkblot
 */
jQuery( function( $ ) {
	$( '#header nav select' ).on( 'change', function() {
		var url = $( this ).find( 'option:selected' ).val();
		
		if ( url ) {
			window.location.href = url;
		}
	} );
} );