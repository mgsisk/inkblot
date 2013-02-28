/** Handle customizor control features.
 * 
 * @package Inkblot
 */
jQuery( function( $ ) {
	/** Sideload subtlepatterns.com background images to prevent hotlinking. */
	$( document ).ajaxComplete( function() {
		if ( 'none' !== $( '#customize-section-background_image .preview-thumbnail img' ).css( 'display' ) && $( '#customize-section-background_image .preview-thumbnail img' ).attr( 'src' ) && -1 < $( '#customize-section-background_image .preview-thumbnail img' ).attr( 'src' ).indexOf( 'raw.github.com/subtlepatterns/SubtlePatterns/master' ) ) {
			sideload_image( $( '#customize-section-background_image .preview-thumbnail img' ), 'background_image', 'custom-background' );
		}
		
		if ( 'none' !== $( '#customize-section-background_image .preview-thumbnail img' ).css( 'display' ) && $( '#customize-section-inkblot_page_background_image .preview-thumbnail img' ).attr( 'src' ) && -1 < $( '#customize-section-inkblot_page_background_image .preview-thumbnail img' ).attr( 'src' ).indexOf( 'raw.github.com/subtlepatterns/SubtlePatterns/master' ) ) {
			sideload_image( $( '#customize-section-inkblot_page_background_image .preview-thumbnail img' ), 'page_background_image', 'inkblot-page-background' );
		}
	} );
	
	/** Toggle page background controls. */
	if ( !$( '#customize-section-inkblot_page_background_image .preview-thumbnail img' ).attr( 'src' ) ) {
		page_bg_controls( false );
	}
	
	$( document ).on( 'click', '#customize-section-inkblot_page_background_image .remove', function() {
		page_bg_controls( false );
	} );
	
	$( document ).on( 'click', '#customize-section-inkblot_page_background_image [data-customize-tab="uploaded"] a, #customize-section-inkblot_page_background_image [data-customize-tab="inkblot_pagebg_subtlepatterns"] a', function() {
		page_bg_controls( true );
	} );
	
	function page_bg_controls( show ) {
		if ( show ) {
			$( '#customize-control-page_background_repeat,#customize-control-page_background_position_x,#customize-control-page_background_attachment' ).show();
		} else {
			$( '#customize-control-page_background_repeat,#customize-control-page_background_position_x,#customize-control-page_background_attachment' ).hide();
		}
	}
	
	function sideload_image( $e, type, context ) {
		$.post( $( '[data-inkblot-admin-url]' ).data( 'inkblot-admin-url' ), {
			src: $e.attr( 'src' ),
			type: type,
			context: context,
			inkblot_admin_ajax: 'InkblotConfig::ajax_sideload_image'
		}, function( data ) {
			$e.attr( 'src', data );
		} );
	}
} );