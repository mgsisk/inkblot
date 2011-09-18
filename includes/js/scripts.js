jQuery( document) . ready( function( $ ) {
	/** Sub-page dropdown menus */
	$( '#head .navi>li' ) . hover(
		function() { $( this ) . children( 'ul' ) . fadeIn(); },
		function() { $( this ) . children( 'ul' ) . fadeOut(); }
	);
	$( '#head .navi>li ul li' ) . hover(
		function() {
			var v_offset = $( this ) . height() * $( this ) . parents( 'ul' ) .children( 'li' ) . index( this );
			var h_offset = $( this ) . width();
			$( this ) . children( 'ul' ) . css( 'top', v_offset ) . css( 'left', h_offset ) . fadeIn();
		},
		function() { $( this ) . children( 'ul' ) . fadeOut(); }
	);
	
	/** Dynamic transcript display */
	$( '#transcript-title' ) . toggle (
		function() { $( '#transcript' ) . slideDown(); },
		function() { $( '#transcript' ) . slideUp(); }
	);
} );