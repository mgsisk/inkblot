/** Responsive menu script.
 * 
 * @package Inkblot
 */
jQuery( function( $ ) {
	$( '#header nav' ).on( 'click', function() {
		if ( Modernizr.mq( 'only screen and ( max-width: 45em )' ) ) {
			$( this ).children().toggle();
		}
	} );
	
	$( window ).on( 'resize', function() {
		if ( Modernizr.mq( 'only screen and ( min-width: 45em )' ) ) {
			$( '#header nav' ).children().show();
		} else {
			$( '#header nav' ).children().hide();
		}
	} ).trigger( 'resize' );
} );