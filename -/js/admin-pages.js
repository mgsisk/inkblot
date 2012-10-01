/** Handle dynamic template options display.
 * 
 * @package Inkblot
 */
jQuery( function( $ ) {
	$( '#page_template' ).on( 'change', function() {
		$( '#inkblot-options .inside > div' ).hide();
		
		if ( -1 !== $( this ).val().indexOf( 'webcomic/' ) ) {
			$( '[data-inkblot-options="' + $( this ).val() + '"]' ).show();
		} else {
			$( '[data-inkblot-options="none"]' ).show();
		}
	} ).trigger( 'change' );
} );