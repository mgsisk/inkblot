/**
 * This document contains javascript necessary to enhance certain Webcomic functions.
 * 
 * @package webcomic
 * @since 3
 */
jQuery( document ) . ready( function( $ ) {
	/** Dynamic transcript display */
	$( '#transcripts h1' ) . toggle (
		function() { $( this ) . siblings( 'article' ) . slideDown( 'fast' ); },
		function() { $( this ) . siblings( 'article' ) . slideUp( 'fast' ); }
	);
	
	$( '#transcribeform h2' ) . toggle (
		function() { $( this ) . siblings( 'p' ) . slideDown( 'fast' ); },
		function() { $( this ) . siblings( 'p' ) . slideUp( 'fast' ); }
	);
} );