/** Handle dynamic theme customization previews.
 * 
 * @package Inkblot
 */
( function( $ ) {
	/** Update the site name. */
	wp.customize( 'blogname', function( value ) { value.bind( function( to ) {
		$( '#header h1' ).html( to );
	} ); } );
	
	/** Update the site description. */
	wp.customize( 'blogdescription', function( value ) { value.bind( function( to ) {
		$( '#header h2' ).html( to );
	} ); } );
	
	/** Update the header text color. */
	wp.customize( 'header_textcolor', function( value ) { value.bind( function( to ) {
		if ( 'blank' === to ) {
			$( '#header hgroup' ).hide();
		} else {
			$( '#header hgroup' ).show();
			$( '#header hgroup, #header hgroup a' ).css( 'color', to );
		}
	} ) } );
	
	/** Update the header image. */
	wp.customize( 'header_image', function( value ) { value.bind( function( to ) {
		if ( to && 'remove-header' !== to ) {
			$( '#header hgroup' ).after( '<a href="#"><img src="' + to + '"></a>' );
		} else {
			$( '#header hgroup + a' ).remove();
		}
	} ) } );
	
	/** Update the background color. */
	wp.customize( 'background_color', function( value ) { value.bind( function( to ) {
		$( 'body' ).css( 'background-color', to );
	} ); } );
	
	/** Update the background image. */
	wp.customize( 'background_image', function( value ) { value.bind( function( to ) {
		$( 'body' ).css( 'background-image', to ? 'url(' + to + ')' : 'none' );
	} ); } );
	
	/** Update the background repeat. */
	wp.customize( 'background_repeat', function( value ) { value.bind( function( to ) {
		$( 'body' ).css( 'background-repeat', to );
	} ); } );
	
	/** Update the background position. */
	wp.customize( 'background_position_x', function( value ) { value.bind( function( to ) {
		$( 'body' ).css( 'background-position', 'top ' + to );
	} ); } );
	
	/** Update the background attachment. */
	wp.customize( 'background_attachment', function( value ) { value.bind( function( to ) {
		$( 'body' ).css( 'background-attachment', to );
	} ); } );
	
	/** Update the page font. */
	wp.customize( 'page_font', function( value ) { value.bind( function( to ) {
		if ( 0 === to ) {
			$( 'body' ).css( 'font-family', 'sans-serif' );
		} else {
			$( 'head' ).append( '<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + to + '">' );
			$( 'body' ).css( 'font-family', to.replace( /\+/g, ' ' ).substr( 0, to.indexOf( ':' ) ) );
		}
	} ); } );
	
	/** Update the title font. */
	wp.customize( 'title_font', function( value ) { value.bind( function( to ) {
		if ( 0 === to ) {
			$( 'h1,h2,h3,h4,h5,h6' ).css( 'font-family', 'inherit' );
		} else {
			$( 'head' ).append( '<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + to + '">' );
			$( 'h1,h2,h3,h4,h5,h6' ).css( 'font-family', to.replace( /\+/g, ' ' ).substr( 0, to.indexOf( ':' ) ) );
		}
	} ); } );
	
	/** Update the title font. */
	wp.customize( 'trim_font', function( value ) { value.bind( function( to ) {
		if ( 0 === to ) {
			$( '#header nav,#header nav select,#footer,.post-webcomic nav' ).css( 'font-family', 'inherit' );
		} else {
			$( 'head' ).append( '<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + to + '">' );
			$( '#header nav,#header nav select,#footer,.post-webcomic nav' ).css( 'font-family', to.replace( /\+/g, ' ' ).substr( 0, to.indexOf( ':' ) ) );
		}
	} ); } );
	
	/** Update the font size. */
	wp.customize( 'font_size', function( value ) { value.bind( function( to ) {
		$( 'html' ).css( 'font-size', to + '%' );
	} ); } );
	
	/** Update the page color. */
	wp.customize( 'page_color', function( value ) { value.bind( function( to ) {
		$( '#page' ).css( 'background-color', to );
		$( '.post-webcomic nav.above' ).css( 'border-color', to );
	} ); } );
	
	/** Update the text color. */
	wp.customize( 'text_color', function( value ) { value.bind( function( to ) {
		$( '#page' ).css( 'color', to );
	} ); } );
	
	/** Update the link color. */
	wp.customize( 'link_color', function( value ) { value.bind( function( to ) {
		var color;
		
		$( '#content a:not( .post-webcomic nav a, .post-actions a, .post-comments-link a, .comment-actions a )' ).css( 'color', to );
		
		$( '.post-actions a, .post-comments-link a, .comment-actions a' ).hover( function() {
			color = $( this ).css( 'background-color' );
			$( this ).css( 'background-color', to );
		}, function() {
			$( this ).css( 'background-color', color );
		} );
	} ); } );
	
	/** Update the link hover color. */
	wp.customize( 'link_hover_color', function( value ) { value.bind( function( to ) {
		var color;
		
		$( '#content a:not( .post-webcomic nav a, .post-actions a, .post-comments-link a, .comment-actions a )' ).hover( function() {
			color = $( this ).css( 'color' );
			$( this ).css( 'color', to );
		}, function() {
			$( this ).css( 'color', color );
		} );
	} ); } );
	
	/** Update the trim color. */
	wp.customize( 'trim_color', function( value ) { value.bind( function( to ) {
		$( '#page,blockquote,pre,td,nav.posts,nav.posts-paged,nav.comments-paged,.post-footer,.comment,.trackback,.comment .comment' ).css( 'border-color', to );
		$( '#header nav,#header nav select,#header nav ul ul,#footer,.post-comments-link a,.post-actions a,.comment-actions a,#commentform .required,.webcomic-transcribe-form .required,.post-webcomic nav' ).css( 'background-color', to );
	} ); } );
	
	/** Update the trim text color. */
	wp.customize( 'trim_text_color', function( value ) { value.bind( function( to ) {
		$( '#header nav,#header nav select,#footer,.post-comments-link a,.post-actions a,.comment-actions a,#commentform .required,.webcomic-transcribe-form .required,.post-webcomic nav' ).css( 'color', to );
	} ); } );
	
	/** Update the trim link color. */
	wp.customize( 'trim_link_color', function( value ) { value.bind( function( to ) {
		$( '#header nav a,#footer a,.post-webcomic nav a' ).css( 'color', to );
	} ); } );
	
	/** Update the trim link hover color. */
	wp.customize( 'trim_link_hover_color', function( value ) { value.bind( function( to ) {
		var color;
		
		$( '#header nav a,#header .current_page_item a,#header .current_page_ancestor a,#footer a,.post-webcomic nav a' ).hover( function() {
			color = $( this ).css( 'color' );
			$( this ).css( 'color', to );
		}, function() {
			$( this ).css( 'color', color );
		} );
	} ); } );
	
	/** Update the content layout. */
	wp.customize( 'content', function( value ) { value.bind( function( to ) {
		$( 'body' ).removeClass( 'one-column two-column-left two-column-right three-column-left three-column-right three-column-center' ).addClass( to );
		inkblot_content_width();
	} ); } );
	
	/** Update the layout min-width. */
	wp.customize( 'min_width', function( value ) { value.bind( function( to ) {
		$( '#page' ).css( 'min-width', ( 0 === to || '0' === to ) ? '' : to + 'px' );
	} ); } );
	
	/** Update the layout max-width. */
	wp.customize( 'max_width', function( value ) { value.bind( function( to ) {
		$( '#page' ).css( 'max-width', ( 0 === to || '0' === to ) ? '' : to + 'px' );
	} ); } );
	
	/** Update the primary sidebar width. */
	wp.customize( 'sidebar1_width', function( value ) { value.bind( function( to ) {
		$( '#sidebar1' ).css( 'width', to + '%' );
		inkblot_content_width();
	} ); } );
	
	/** Update the primary sidebar width. */
	wp.customize( 'sidebar2_width', function( value ) { value.bind( function( to ) {
		$( '#sidebar2' ).css( 'width', to + '%' );
		inkblot_content_width();
	} ); } );
	
	/** Update the page background image. */
	wp.customize( 'page_background_image', function( value ) { value.bind( function( to ) {
		$( '#page' ).css( 'background-image', to ? 'url(' + to + ')' : 'none' );
	} ); } );
	
	/** Update the page background repeat. */
	wp.customize( 'page_background_repeat', function( value ) { value.bind( function( to ) {
		$( '#page' ).css( 'background-repeat', to );
	} ); } );
	
	/** Update the page background position. */
	wp.customize( 'page_background_position_x', function( value ) { value.bind( function( to ) {
		$( '#page' ).css( 'background-position', 'top ' + to );
	} ); } );
	
	/** Update the page background attachment. */
	wp.customize( 'page_background_attachment', function( value ) { value.bind( function( to ) {
		$( '#page' ).css( 'background-attachment', to );
	} ); } );
	
	/** Update the webcomic position. */
	wp.customize( 'webcomic_content', function( value ) { value.bind( function( to ) {
		if ( to ) {
			$( '#webcomic' ).prependTo( 'main' );
		} else {
			$( '#webcomic' ).prependTo( '#content' );
		}
	} ); } );
	
	/** Update the webcomic dimensions. */
	wp.customize( 'webcomic_resize', function( value ) { value.bind( function( to ) {
		$( '#webcomic .webcomic-image img' ).css( 'max-width', to ? '100%' : 'none' );
	} ); } );
	
	/** Update the top navigation visibility. */
	wp.customize( 'webcomic_nav_above', function( value ) { value.bind( function( to ) {
		if ( to ) {
			$( '#webcomic nav.above' ).show();
		} else {
			$( '#webcomic nav.above' ).hide();
		}
	} ); } );
	
	/** Update the bottom navigation visibility. */
	wp.customize( 'webcomic_nav_below', function( value ) { value.bind( function( to ) {
		if ( to ) {
			$( '#webcomic nav.below' ).show();
		} else {
			$( '#webcomic nav.below' ).hide();
		}
	} ); } );
	
	/** Update the bottom navigation visibility. */
	wp.customize( 'webcomic_home_hook', function( value ) { value.bind( function( to ) {
		if ( to ) {
			$( '.home #webcomic,.blog #webcomic' ).show();
		} else {
			$( '.home #webcomic,.blog #webcomic' ).hide();
		}
	} ); } );
	
	/** Update the last webcomic first image. */
	wp.customize( 'first_webcomic_image', function( value ) { value.bind( function( to ) {
		if ( to ) {
			var alt = $( '#webcomic .first-webcomic-link img' ).length ? $( '#webcomic .first-webcomic-link img' ).attr( 'alt' ) : $( '#webcomic .first-webcomic-link' ).html();
			$( '#webcomic .first-webcomic-link' ).html( '<img src="' + to + '" alt="' + alt + '">' );
		} else {
			$( '#webcomic .first-webcomic-link' ).html( $( '#webcomic .first-webcomic-link img' ).attr( 'alt' ) );
		}
	} ); } );
	
	/** Update the last webcomic link image. */
	wp.customize( 'last_webcomic_image', function( value ) { value.bind( function( to ) {
		if ( to ) {
			var alt = $( '#webcomic .last-webcomic-link img' ).length ? $( '#webcomic .last-webcomic-link img' ).attr( 'alt' ) : $( '#webcomic .last-webcomic-link' ).html();
			$( '#webcomic .last-webcomic-link' ).html( '<img src="' + to + '" alt="' + alt + '">' );
		} else {
			$( '#webcomic .last-webcomic-link' ).html( $( '#webcomic .last-webcomic-link img' ).attr( 'alt' ) );
		}
	} ); } );
	
	/** Update the previous webcomic link image. */
	wp.customize( 'previous_webcomic_image', function( value ) { value.bind( function( to ) {
		if ( to ) {
			var alt = $( '#webcomic .previous-webcomic-link img' ).length ? $( '#webcomic .previous-webcomic-link img' ).attr( 'alt' ) : $( '#webcomic .previous-webcomic-link' ).html();
			$( '#webcomic .previous-webcomic-link' ).html( '<img src="' + to + '" alt="' + alt + '">' );
		} else {
			$( '#webcomic .previous-webcomic-link' ).html( $( '#webcomic .previous-webcomic-link img' ).attr( 'alt' ) );
		}
	} ); } );
	
	/** Update the next webcomic link image. */
	wp.customize( 'next_webcomic_image', function( value ) { value.bind( function( to ) {
		if ( to ) {
			var alt = $( '#webcomic .next-webcomic-link img' ).length ? $( '#webcomic .next-webcomic-link img' ).attr( 'alt' ) : $( '#webcomic .next-webcomic-link' ).html();
			$( '#webcomic .next-webcomic-link' ).html( '<img src="' + to + '" alt="' + alt + '">' );
		} else {
			$( '#webcomic .next-webcomic-link' ).html( $( '#webcomic .next-webcomic-link img' ).attr( 'alt' ) );
		}
	} ); } );
	
	/** Update the random webcomic link image. */
	wp.customize( 'random_webcomic_image', function( value ) { value.bind( function( to ) {
		if ( to ) {
			var alt = $( '#webcomic .random-webcomic-link img' ).length ? $( '#webcomic .random-webcomic-link img' ).attr( 'alt' ) : $( '#webcomic .random-webcomic-link' ).html();
			$( '#webcomic .random-webcomic-link' ).html( '<img src="' + to + '" alt="' + alt + '">' );
		} else {
			$( '#webcomic .random-webcomic-link' ).html( $( '#webcomic .random-webcomic-link img' ).attr( 'alt' ) );
		}
	} ); } );
	
	/** Update container widths and positions. */
	function inkblot_content_width() {
		var width    = 100;
		var sidebar1 = Math.floor( parseFloat( $( '#sidebar1' ).outerWidth() / $( '#page' ).outerWidth() ) * 100 );
		var sidebar2 = Math.floor( parseFloat( $( '#sidebar2' ).outerWidth() / $( '#page' ).outerWidth() ) * 100 );
		
		if ( $( '#sidebar2' ).is( ':visible' ) ) {
			width = Math.floor( 98 - sidebar1 - sidebar2 );
		} else if ( $( '#sidebar1' ).is( ':visible' ) ) {
			width = Math.floor( 99 - sidebar1 );
		}
		
		if ( $( 'body' ).hasClass( 'three-column-center' ) ) {
			$( 'main ' ).css( { 'left': sidebar1 + 1 + '%', 'position': 'relative' } );
			$( '#sidebar1' ).css( { 'left': '-' + width + '%', 'position': 'relative' } );
		} else {
			$( 'main ' ).css( { 'left': '0', 'position': 'static' } );
			$( '#sidebar1' ).css( { 'left': '-' + width + '%', 'position': 'static' } );
		}
		
		$( 'main ' ).css( 'width', width + '%' );
	}
} )( jQuery )