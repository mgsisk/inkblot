<?php
/** Set the content width */
if ( !isset( $content_width ) ) $content_width = 640;

/** Load the core */
if ( !class_exists( 'mgs_core' ) ) require_once( 'includes/mgs-core.php' );

/** Defines all theme functionality, extending mgs_core */
class inkblot extends mgs_core {
	/** Override mgs_core variables */
	protected $name    = 'inkblot';
	protected $version = '3.0.5';
	protected $file    = __FILE__;
	protected $type    = 'theme';
	
	/** Run-once installation */
	function install() {
		$this->option( array(
			'version'                  => $this->version,
			'layout'                   => '1c1',
			'dim_alignment'            => 'center',
			'dim_webcomic'             => 600,
			'dim_site'                 => 600,
			'dim_content'              => 600,
			'dim_sidebar1'             => 0,
			'dim_sidebar2'             => 0,
			'header_w'                 => 600,
			'header_h'                 => 90,
			'post_w'                   => get_option( 'thumbnail_size_w' ),
			'post_h'                   => get_option( 'thumbnail_size_h' ),
			'home_webcomic_toggle'     => true,
			'home_webcomic_order'      => 'DESC',
			'home_webcomic_collection' => false,
			'single_webcomic_link'     => false,
			'archive_webcomic_toggle'  => true,
			'archive_webcomic_size'    => 'small',
			'embed_webcomic_toggle'    => false,
			'embed_webcomic_format'    => 'shtml',
			'embed_webcomic_size'      => 'small',
			'prints_original_toggle'   => false
		) );
	}
	
	/** Upgrade older versions */
	function upgrade() {
		$this->option( 'version', $this->version );
	}
	
	/** Downgrade newer versions */
	function downgrade() {
		$this->option( 'version', $this->version );
	}
	
	/** Uninstall the theme */
	function uninstall() {
		$this->option( array(
			'version'   => $this->version,
			'uninstall' => true
		) );
	}
	
	
	
	////
	// Hooks - These functions hook into WordPress to add, change, and remove functionality.
	////
	
	/** Add standard features */
	function hook_after_setup_theme() {
		$this->domain();
		
		//remove_action( 'wp_head', 'rsd_link' );
		//remove_action( 'wp_head', 'wlwmanifest_link' );
		
		define( 'HEADER_IMAGE', '%s/includes/images/header.jpg' );
		define( 'HEADER_TEXTCOLOR', '333' );
		define( 'HEADER_IMAGE_WIDTH', $this->option( 'header_w' ) );
		define( 'HEADER_IMAGE_HEIGHT', $this->option( 'header_h' ) );
		
		set_post_thumbnail_size( $this->option( 'post_w' ), $this->option( 'post_h' ), true );
		
		add_editor_style( 'style-editor.css' );
		add_theme_support( 'nav-menus' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_custom_background( array( &$this, 'custom_background' ) );
		add_custom_image_header( array( &$this, 'custom_header' ), array( &$this, 'admin_custom_header' ) );
		
		register_nav_menus( array(
			'navbar' => __( 'Navbar', 'inkblot' )
		) );
		
		register_default_headers( array (
			'default' => array (
				'url'           => '%s/includes/images/headers/header.png',
				'thumbnail_url' => '%s/includes/images/headers/thumbs/header.png',
				'description'   => ucwords( str_replace( '_', ' ', $this->name ) )
			)
		) );
	}
	
	/** Add theme scripts */
	function hook_template_redirect() {
		if ( is_singular() ) wp_enqueue_script( 'comment-reply', '', '', '', true );
		
		wp_enqueue_script( 'html5shiv', 'http://html5shiv.googlecode.com/svn/trunk/html5.js' );
		wp_enqueue_script( 'inkblot-scripts', $this->url . '/includes/scripts.js', array( 'jquery' ), '', true );
	}
	
	/** Add widgetized areas */
	function hook_init() {
		$sidebars = array(
			'inkblot-sidebar1'          => array( __( 'Sidebar 1', 'inkblot' ), __( 'The first sidebar, used in both two and three-column layouts.', 'inkblot' ) ),
			'inkblot-sidebar2'          => array( __( 'Sidebar 2', 'inkblot' ), __( 'The second sidebar, used in three-column layouts.', 'inkblot' ) ),
			'inkblot-page-above'        => array( __( 'Page Above', 'inkblot' ), __( 'Located at the very top of every page, before the header.', 'inkblot' ) ),
			'inkblot-page-below'        => array( __( 'Page Below', 'inkblot' ), __( 'Located at the very bottom of every page, below the footer.', 'inkblot' ) ),
			'inkblot-webcomic-above'    => array( __( 'Webcomic Above', 'inkblot' ), __( 'Located above the webcomic on the home page and single-webcomic pages.', 'inkblot' ) ),
			'inkblot-webcomic-below'    => array( __( 'Webcomic Below', 'inkblot' ), __( 'Located below the webcomic on the home page and single-webcomic pages.', 'inkblot' ) ),
			'inkblot-content-above'     => array( __( 'Content Above', 'inkblot' ), __( 'Located above the content block, just after the webcomic.', 'inkblot' ) ),
			'inkblot-content-below'     => array( __( 'Content Below', 'inkblot' ), __( 'Located below the content block, just before the footer.', 'inkblot' ) ),
			'inkblot-transcripts-above' => array( __( 'Transcripts Above', 'inkblot' ), __( 'Located above the transcripts section on single-webcomic pages.', 'inkblot' ) ),
			'inkblot-transcripts-below' => array( __( 'Transcripts Below', 'inkblot' ), __( 'Located below the transcripts section on single-webcomic pages.', 'inkblot' ) ),
			'inkblot-comments-above'    => array( __( 'Comments Above', 'inkblot' ), __( 'Located above the comments section on single-post pages.', 'inkblot' ) ),
			'inkblot-comments-below'    => array( __( 'Comments Below', 'inkblot' ), __( 'Located below the comments section on single-post pages.', 'inkblot' ) )
		);
		
		foreach ( $sidebars as $k => $v ) register_sidebar( array( 'id' => $k, 'name' => $v[ 0 ], 'description' => $v[ 1 ], 'before_widget' => '<figure id="%s" class="widget %s">', 'after_widget' => '</figure>', 'before_title' => '<figcaption>', 'after_title' => '</figcaption>' ) );
	}
	
	/** Add custom body classes */
	function hook_body_class( $classes ) {
		global $is_lynx, $is_gecko, $is_winIE, $is_macIE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
		
		if ( $is_lynx )       $classes[] = 'browser-lynx';
		elseif ( $is_gecko )  $classes[] = 'browser-gecko';
		elseif ( $is_winIE )  $classes[] = 'browser-winie';
		elseif ( $is_macIE )  $classes[] = 'browser-macie';
		elseif ( $is_opera )  $classes[] = 'browser-oepra';
		elseif ( $is_NS4 )    $classes[] = 'browser-netscape';
		elseif ( $is_safari ) $classes[] = 'browser-safari';
		elseif ( $is_chrome ) $classes[] = 'browser-chrome';
		else                  $classes[] = 'browser-unknown';
		
		if ( $is_iphone ) $classes[] = 'device-iphone';
		
		$classes[] = 'layout-align-' . $this->option( 'dim_alignment' );
		$classes[] = 'layout-' . $this->option( 'layout' );
		
		return $classes;
	}
	
	/** Add custom bloginfo */
	function hook_bloginfo( $r, $s ) {
		global $wpdb, $wp_query;
		
		if ( 'meta_description' == $s ) {
			if ( is_single() || is_attachment() || is_page() )
				$r = ( get_the_excerpt() ) ? get_the_excerpt() : wp_trim_excerpt( '' );
			elseif ( is_category() || is_tag() || is_tax() || is_author() ) {
				$o = $wp_query->get_queried_object();
				$r = implode( ' ', array_slice( explode( ' ', trim( htmlentities( strip_tags( $o->description ) ) ) ), 0, apply_filters( 'excerpt_length', 55 ) ) );
			} else
				$r = get_option( 'blogdescription' );
		} elseif ( 'copyright' == $s ) {
			$c = current( $wpdb->get_results( "SELECT YEAR( min( post_date ) ) AS start, YEAR( max( post_date ) ) AS end FROM $wpdb->posts WHERE post_status = 'publish'" ) );
			$r = ( $c->start == $c->end ) ? "&copy; $c->end" : "&copy; $c->start &ndash; $c->end";
		}
		
		return $r;
	}
	
	/** Add custom url bloginfo */
	function hook_bloginfo_url( $r, $s ) {
		if ( 'icon_url' == $s )
			$r = $this->url . '/includes/images/icon.png';
		
		return $r;
	}
	
	/** Add theme avatar */
	function hook_avatar_defaults( $d ) {
		$d[ $this->url . '/includes/images/avatar.png' ] = ucwords( str_replace( '_', ' ', $this->name ) );
		
		return $d;
	}
	
	/** Remove the generator <meta> field
	function hook_the_generator() {
		return false;
	}
	
	/** Remove recent comments widget styles */
	function hook_widgets_init() {
		global $wp_widget_factory;
		
		remove_action( 'wp_head', array( $wp_widget_factory->widgets[ 'WP_Widget_Recent_Comments' ], 'recent_comments_style' ) );
	}
	
	/** Change wp_title for better search engine optimization */
	function hook_wp_title( $title, $sep, $seplocation ) {
		if ( is_feed() )
			return $title;
		
		global $paged, $page;
		
		$a = explode( " $sep ", $title );
		$p = ( 1 < $paged || 1 < $page ) ? sprintf( __( 'Page %s', 'inkblot' ), max( $paged, $page ) ) : '';
		$n = array( $p, get_bloginfo( 'name', 'display' ), get_bloginfo( 'description', 'display' ) );
		
		if ( !is_home() || !is_front_page() )
			unset( $n[ 2 ] );
		
		$a = ( 'right' == $seplocation ) ? array_merge( $a, $n ) : array_merge( $n, $a );
		
		foreach ( $a as $k => $v )
			if ( !$v )
				unset( $a[ $k ] );
		
		return implode( " $sep ", $a );
	}
	
	/** Change the exceprt word length */
	function hook_excerpt_length( $l ) {
		return 60;
	}
	
	/** Change the excerpt 'Read More' link */
	function hook_excerpt_more( $m ) {
		return ' <a href="' . get_permalink() . '" title="' . __( 'Continue reading', 'inkblot' ) . '">&hellip;</a>';
	}
	function hook_custom_excerpt_more( $o ) {
		if ( has_excerpt() && !is_attachment() )
			$o .= ' <a href="' . get_permalink() . '" title="' . __( 'Continue reading', 'inkblot' ) . '">&hellip;</a>';
		
		return $o;
	}
	
	/** Change the gallery shortcode to use HTML5 */
	function hook_post_gallery( $null, $attr ) {
		global $post, $wp_locale;
		static $instance = 0; $instance++;
		
		if ( isset( $attr[ 'orderby' ] ) ) {
			$attr[ 'orderby' ] = sanitize_sql_orderby( $attr[ 'orderby' ] );
			
			if ( empty( $attr[ 'orderby' ] ) )
				unset( $attr[ 'orderby' ] );
		}
		
		extract( shortcode_atts( array(
			'id'         => $post->ID,
			'size'       => 'thumbnail',
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'itemtag'    => 'figure',
			'icontag'    => 'div',
			'include'    => false,
			'exclude'    => false,
			'columns'    => false,
			'captiontag' => 'figcaption'
		), $attr ) );
		
		$id          = intval( $id );
		$orderby     = ( 'RAND' == $order ) ? 'none' : $orderby;
		$attachments = array();
		$itemtag     = tag_escape( $itemtag );
		$captiontag  = tag_escape( $captiontag );
		$columns     = intval( $columns );
		
		if ( !empty( $include ) ) {
			$include      = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
			
			foreach ( $_attachments as $k => $v )
				$attachments[ $v->ID ] = $_attachments[ $k ];
		} elseif ( !empty( $exclude ) ) {
			$exclude     = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		} else
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		
		if ( empty( $attachments ) )
			return false;
		
		if ( is_feed() ) {
			$r = "\n";
			
			foreach ( $attachments as $att_id => $attachment )
				$r .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
		} else {
			$i = 0;
			$r = '<div id="gallery-' . $instance . '" class="gallery gallery-' . $id . '">';
			
			foreach ( $attachments as $id => $attachment ) {
				$l  = isset( $attr[ 'link' ] ) && 'file' == $attr[ 'link' ] ? wp_get_attachment_link( $id, $size, false, false ) : wp_get_attachment_link( $id, $size, true, false );
				$r .= '<' . $itemtag . ' class="gallery-item"><' . $icontag . ' class="gallery-icon">' . $l . '</' . $icontag . '>';
				$r .= ( $captiontag && trim( $attachment->post_excerpt ) ) ? '<' . $captiontag . ' class="gallery-caption">' . wptexturize( $attachment->post_excerpt ) . '</' . $captiontag . '></' . $itemtag . '>' : '</' . $itemtag . '>';
				
				$i++;
				
				$r .= ( 0 < $columns && 0 == ( $i % $columns ) ) ? '<hr>' : '';
			}
			
			$r .= '<hr></div>';
			$r  = str_replace( '<hr><hr></div>', '<hr></div>', $r );
		}
		
		return $r;
	}
	
	/** Display standard <head> information */
	function hook_wp_head_0() { ?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<meta name="description" content="<?php bloginfo( 'meta_description' ); ?>">
		<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<link rel="icon" href="<?php bloginfo( 'icon_url' ); ?>">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php $this->custom_layout();
	}
	
	/** Display meta information */
	function hook_wp_meta() { ?>
		<a href="#body" title="Return to Top"><?php bloginfo( 'copyright' ); ?> <?php bloginfo( 'name' ); ?></a> | <?php printf( __( 'Powered by <a href="%s">WordPress</a> with <a href="%s">Webcomic</a> | <a href="%s">Subscribe</a>', 'inkblot' ), 'http://wordpress.org/', 'http://webcomicms.net/', get_bloginfo( 'rss2_url' ) ); ?>
		<?php
		
	}
	
	
	
	////
	// Utilities
	////
	
	/** Display custom background CSS */
	function custom_background() {
		$background = get_background_image();
		$color = get_background_color();
		
		if ( !$background && !$color )
			return false;
		
		switch ( get_theme_mod( 'background_repeat', 'repeat' ) ) {
			case "no-repeat": $repeat = "no-repeat"; break;
			case "repeat-x": $repeat = "repeat-x"; break;
			case "repeat-y": $repeat = "repeat-y"; break;
			default: $repeat = "repeat";
		}
		
		switch ( get_theme_mod( 'background_position', 'left' ) ) {
			case "center": $position = "0 50%"; break;
			case "right": $position = "0 100%"; break;
			default: $position = "0 0";
		}
		
		$attachment = ( 'scroll' == get_theme_mod( 'background_attachment', 'fixed' ) ) ? 'scroll' : 'fixed';
		$image = ( !empty( $background ) ) ? "url($background) $repeat $attachment $position" : '';
		$color = ( !empty( $color ) ) ? "#$color" : '';
		
		echo "<style>html{background:$color $image}</style>";
	}
	
	/** Display custom header CSS */
	function custom_header() {
		$background = ( get_header_image() ) ? '#header hgroup a{background:url(' . get_header_image() . ');display:block;height:' . HEADER_IMAGE_HEIGHT . 'px;width:' . HEADER_IMAGE_WIDTH . 'px}' : '';
		$color = get_header_textcolor();
		
		if ( !$background && !$color )
			return false;
		
		$text = ( 'blank' == $color ) ? '#header hgroup h1,#header hgroup h2{display:none}' : "#header hgroup,#header hgroup a{color:#$color}";
		
		echo '<style>' . $background . $text . "</style>";
	}
	
	/** Display custom header in administrative dashboard CSS */
	function admin_custom_header() {
		echo '<style>#headimg{height:' . HEADER_IMAGE_HEIGHT . 'px;width:' . HEADER_IMAGE_WIDTH . 'px}</style>';
	}
	
	/** Display custom layout CSS */
	function custom_layout() {
		$l = explode( 'c', $this->option( 'layout' ) );
		$s = false;
		
		if ( 'left' == $this->option( 'dim_alignment' ) )
			$s = '#wrap{margin:0 auto 0 0;';
		elseif ( 'right' == $this->option( 'dim_alignment' ) )
			$s = '#wrap{margin:0 0 0 auto;';
		else
			$s = '#wrap{';
		
		$s .= 'width:' . $this->option( 'dim_site' ) . 'px}';
		
		if ( 3 == $l[ 0 ] ) {
			$s1 = $s2 = false;
			$s .= '#content{width:' . $this->option( 'dim_content' ) . 'px;';
			
			if ( 3 == $l[ 1 ] || 6 == $l[ 1 ] ) {
				$s .= 'float:left;}';
				$s1 = 'float:left;';
				$s2 = 'float:right;';
			} elseif ( 2 == $l[ 1 ] || 5 == $l[ 1 ] )
				$s .= 'float:right}#sidebar1,#sidebar2{float:left}';
			else
				$s .= 'float:left}#sidebar1,#sidebar2{float:right}';
			
			$s .= '#sidebar1{' . $s1 .'width:' . $this->option( 'dim_sidebar1' ) . 'px}#sidebar2{' . $s2 .'width:' . $this->option( 'dim_sidebar2' ) . 'px}';
			
			if ( 6 == $l[ 1 ] )
				$s = str_replace( '#content', '#main', $s );
		} elseif ( 2 == $l[ 0 ] ) {
			$s .= '#sidebar2{display:none}#content{width:' . $this->option( 'dim_content' ) . 'px;' . ( ( $l[ 1 ] % 2 ) ? 'float:left}#sidebar1{float:right;width:' . $this->option( 'dim_sidebar1' ) . 'px}' : 'float:right}#sidebar1{float:left;width:' . $this->option( 'dim_sidebar1' ) . 'px}' );
			
			if ( 3 == $l[ 1 ] || 4 == $l[ 1 ] )
				$s = str_replace( '#content', '#main', $s );
		} else
			$s .= '#sidebar1,#sidebar2{display:none}';
		
		echo '<style>' . $s . '</style>';
	}
	
	/** Return paginated posts links */
	function get_paginated_posts_links( $args = array() ) {
		global $wp_query, $wp_rewrite;
		
		$p = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		
		$defaults = array(
			'base'     => @add_query_arg( 'paged','%#%' ),
			'format'   => false,
			'total'    => $wp_query->max_num_pages,
			'current'  => $p,
			'add_args' => false
		); $args = wp_parse_args( $args, $defaults );
		
		$args[ 'base' ]     = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%', 'page' ) : $args[ 'base' ];
		$args[ 'add_args' ] = ( !empty( $wp_query->query_vars[ 's' ] ) ) ? array( 's' => get_query_var( 's' ) ) : $args[ 'add_args' ];
		
		return preg_replace( '/>...</', '>&hellip;<', paginate_links( $args ) );
	}
	
	
	
	////
	// Administration - These functions add various administrative pages and options.
	////
	
	/** Add the custom layout page */
	function hook_admin_menu() {
		add_theme_page( __( 'Layout', 'inkblot' ), __( 'Layout', 'inkblot' ), 'edit_themes', basename( __FILE__ ), array( &$this, 'admin_layout' ) );
		add_meta_box( 'inkblot', __( 'Inkblot', 'inkblot' ), array( &$this, 'admin_metabox' ), 'page', 'normal', 'high' );
	}
	
	function hook_admin_notices() {
		if ( $this->update ) { ?><div id="message" class="updated fade"><p><?php echo implode( '</p><p>', $this->update ); ?></p></div><?php }
		if ( $this->errors ) { ?><div id="message" class="error"><p><?php echo implode( '</p><p>', $this->errors ); ?></p></div><?php }
	}
	
	function hook_admin_init() {
		if ( isset( $_REQUEST[ 'action' ] ) && 'inkblot_settings' == $_REQUEST[ 'action' ] ) {
			check_admin_referer( 'inkblot_settings' );
			
			$new[ 'version' ]                  = $this->version;
			$new[ 'layout' ]                   = $_REQUEST[ 'layout' ];
			$new[ 'dim_alignment' ]            = $_REQUEST[ 'dim_alignment' ];
			$new[ 'dim_webcomic' ]             = intval( $_REQUEST[ 'dim_webcomic' ] );
			$new[ 'dim_content' ]              = intval( $_REQUEST[ 'dim_content' ] );
			$new[ 'dim_sidebar1' ]             = intval( $_REQUEST[ 'dim_sidebar1' ] );
			$new[ 'dim_sidebar2' ]             = intval( $_REQUEST[ 'dim_sidebar2' ] );
			$new[ 'dim_site' ]                 = intval( $_REQUEST[ 'dim_site' ] );
			$new[ 'header_w' ]                 = intval( $_REQUEST[ 'header_w' ] );
			$new[ 'header_h' ]                 = intval( $_REQUEST[ 'header_h' ] );
			$new[ 'post_w' ]                   = intval( $_REQUEST[ 'post_w' ] );
			$new[ 'post_h' ]                   = intval( $_REQUEST[ 'post_h' ] );
			$new[ 'home_webcomic_toggle' ]     = ( isset( $_POST[ 'home_webcomic_toggle' ] ) ) ? true : false;
			$new[ 'home_webcomic_order' ]      = $_REQUEST[ 'home_webcomic_order' ];
			$new[ 'home_webcomic_collection' ] = intval( $_REQUEST[ 'home_webcomic_collection' ] );
			$new[ 'single_webcomic_link' ]     = ( isset( $_REQUEST[ 'single_webcomic_toggle' ] ) ) ? $_REQUEST[ 'single_webcomic_link' ] : false;
			$new[ 'archive_webcomic_toggle' ]  = ( isset( $_REQUEST[ 'archive_webcomic_toggle' ] ) ) ? true : false;
			$new[ 'archive_webcomic_size' ]    = $_REQUEST[ 'archive_webcomic_size' ];
			$new[ 'embed_webcomic_toggle' ]    = ( isset( $_POST[ 'embed_webcomic_toggle' ] ) ) ? true : false;
			$new[ 'embed_webcomic_format' ]    = $_REQUEST[ 'embed_webcomic_format' ];
			$new[ 'embed_webcomic_size' ]      = $_REQUEST[ 'embed_webcomic_size' ];
			$new[ 'prints_original_toggle' ]   = ( isset( $_POST[ 'prints_original_toggle' ] ) ) ? true : false;
			
			$this->option( $new );
			$this->update[ 'settings' ] = __( 'Settings saved', 'inkblot' );
		}
	}
	
	function admin_layout() {
		?>
		<style>.form-table input[type=radio]{display:none}.form-table .layout label{border:1px solid transparent;display:block;float:left;padding:.25em}.form-table input[type=radio]:checked + label{background:#ffffe0;border:1px solid #e6db55}</style>
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2><?php _e( 'Custom Layout', 'inkblot' ); ?></h2>
			<form method="post" action="">
				<?php wp_nonce_field( 'inkblot_settings' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row" rowspan="4">
							<?php _e( 'Layout', 'inkblot' ); ?>
							<p class="description"><?php _e( 'A one-column layout focuses primarily on the webcomic, while a two or three-column layout provides greater flexibility for additional content.', 'inkblot' ); ?></p>
						</th>
						<td class="layout"><input type="radio" name="layout" value="1c1" id="l1c1"<?php if ( '1c1' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l1c1"><img src="<?php echo $this->url . '/includes/images/admin/1c1.png'; ?>" alt=""></label></td>
					</tr>
					<tr>
						<td class="layout">
							<input type="radio" name="layout" value="2c1" id="l2c1"<?php if ( '2c1' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l2c1"><img src="<?php echo $this->url . '/includes/images/admin/2c1.png'; ?>" alt=""></label>
							<input type="radio" name="layout" value="2c2" id="l2c2"<?php if ( '2c2' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l2c2"><img src="<?php echo $this->url . '/includes/images/admin/2c2.png'; ?>" alt=""></label>
							<input type="radio" name="layout" value="2c3" id="l2c3"<?php if ( '2c3' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l2c3"><img src="<?php echo $this->url . '/includes/images/admin/2c3.png'; ?>" alt=""></label>
							<input type="radio" name="layout" value="2c4" id="l2c4"<?php if ( '2c4' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l2c4"><img src="<?php echo $this->url . '/includes/images/admin/2c4.png'; ?>" alt=""></label>
						</td>
					</tr>
					<tr>
						<td class="layout">
							<input type="radio" name="layout" value="3c1" id="l3c1"<?php if ( '3c1' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l3c1"><img src="<?php echo $this->url . '/includes/images/admin/3c1.png'; ?>" alt=""></label>
							<input type="radio" name="layout" value="3c2" id="l3c2"<?php if ( '3c2' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l3c2"><img src="<?php echo $this->url . '/includes/images/admin/3c2.png'; ?>" alt=""></label>
							<input type="radio" name="layout" value="3c3" id="l3c3"<?php if ( '3c3' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l3c3"><img src="<?php echo $this->url . '/includes/images/admin/3c3.png'; ?>" alt=""></label>
							<input type="radio" name="layout" value="3c4" id="l3c4"<?php if ( '3c4' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l3c4"><img src="<?php echo $this->url . '/includes/images/admin/3c4.png'; ?>" alt=""></label>
							<input type="radio" name="layout" value="3c5" id="l3c5"<?php if ( '3c5' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l3c5"><img src="<?php echo $this->url . '/includes/images/admin/3c5.png'; ?>" alt=""></label>
							<input type="radio" name="layout" value="3c6" id="l3c6"<?php if ( '3c6' == $this->option( 'layout' ) ) echo ' checked'; ?>><label for="l3c6"><img src="<?php echo $this->url . '/includes/images/admin/3c6.png'; ?>" alt=""></label>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e( 'Alignment:', 'inkblot' ); ?>
							<select name="dim_alignment">
								<option value="left"<?php if ( 'left' == $this->option( 'dim_alignment' ) ) echo ' selected'; ?>><?php _e( 'Left', 'inkblot' ); ?></option>
								<option value="center"<?php if ( 'center' == $this->option( 'dim_alignment' ) ) echo ' selected'; ?>><?php _e( 'Center', 'inkblot' ); ?></option>
								<option value="right"<?php if ( 'right' == $this->option( 'dim_alignment' ) ) echo ' selected'; ?>><?php _e( 'Right', 'inkblot' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row" rowspan="5">
							<?php _e( 'Dimensions', 'inkblot' ); ?>
							<p class="description"><?php _e( 'These numbers specify the widths of the major parts of your site. Only a <code>Webcomic</code> width is necessary; the others will be calculated automatically if not provided.', 'inkblot' ); ?></p>
						</th>
						<td><label><?php _e( 'Webomic', 'inkblot' ); ?> <input type="text" name="dim_webcomic" value="<?php echo $this->option( 'dim_webcomic' ); ?>" class="small-text dim" style="text-align:center"></label></td>
					</tr>
					<tr><td><label><?php _e( 'Content&nbsp;&nbsp;', 'inkblot' ); ?> <input type="text" name="dim_content" value="<?php echo $this->option( 'dim_content' ); ?>" class="small-text dim" style="text-align:center"></label></td></tr>
					<tr><td><label><?php _e( 'Sidebar 1', 'inkblot' ); ?> <input type="text" name="dim_sidebar1" value="<?php echo $this->option( 'dim_sidebar1' ); ?>" class="small-text dim" style="text-align:center"></label></td></tr>
					<tr><td><label><?php _e( 'Sidebar 2', 'inkblot' ); ?> <input type="text" name="dim_sidebar2" value="<?php echo $this->option( 'dim_sidebar2' ); ?>" class="small-text dim" style="text-align:center"></label></td></tr>
					<tr><td><label><?php _e( 'Site Total', 'inkblot' ); ?> <input type="text" name="dim_site" value="<?php echo $this->option( 'dim_site' ); ?>" class="small-text dim" style="text-align:center"></label></td></tr>
					<tr>
						<th scope="row">
							<?php _e( 'Header', 'inkblot' ); ?>
							<p class="description"><?php printf( __( 'These numbers specify the width and height of your <a href="%s">site header</a> in pixels.', 'inkblot' ), admin_url( 'themes.php?page=custom-header' ) ); ?></p>
						</th>
						<td style="vertical-align:top"><input type="text" name="header_w" value="<?php echo $this->option( 'header_w' ); ?>" class="small-text" style="text-align:center"> &#215; <input type="text" name="header_h" value="<?php echo $this->option( 'header_h' ); ?>" class="small-text" style="text-align:center"></td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e( 'Thumbnails', 'inkblot' ); ?>
							<p class="description"><?php _e( 'These numbers specify the width and height (in pixels) to use for post thumbnails.', 'inkblot' ); ?></p>
						</th>
						<td style="vertical-align:top"><input type="text" name="post_w" value="<?php echo $this->option( 'post_w' ); ?>" class="small-text" style="text-align:center"> &#215; <input type="text" name="post_h" value="<?php echo $this->option( 'post_h' ); ?>" class="small-text" style="text-align:center"></td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e( 'Miscellanea', 'inkblot' ) ?>
							<p class="description"><?php _e( 'Additional layout-related options to customize the look of your site.', 'inkblot' ); ?></p>
						</th>
						<td>
							<p>
								<input type="checkbox" name="home_webcomic_toggle" value="1"<?php if ( $this->option( 'home_webcomic_toggle' ) ) echo ' checked'; ?>>
								<?php
									$a = $b = $c = $d = '';
									
									switch ( $this->option( 'home_webcomic_order' ) ) {
										case 'DESC' : $a = ' selected'; break;
										default     : $b = ' selected';
									}
									
									$s = '
									<select name="home_webcomic_order">
										<option value="ASC"' . $b . '>' . __( 'first', 'inkblot' ) . '</option>
										<option value="DESC"' . $a . '>' . __( 'last', 'inkblot' ) . '</option>
									</select>'; unset( $a, $b );
									
									$walker   = new webcomic_Walker_AdminTermDropdown();
									$selected = array( $this->option( 'home_webcomic_collection' ) );
								
									$t = '<select name="home_webcomic_collection"><option value="0">' . __( 'any collection', 'inkblot' ) . '</option>' . $walker->walk( get_terms( 'webcomic_collection', 'get=all' ), 0, array( 'selected' => $selected ) ) . '</select>';
									
									printf( __( 'Show the %s webcomic from %s on the home page', 'inkblot' ), $s, $t );
								?>
							</p>
							<p>
								<input type="checkbox" name="single_webcomic_toggle" value="1"<?php if ( $this->option( 'single_webcomic_link' ) ) echo ' checked'; ?>>
								<?php
									$a = $b = '';
									
									switch ( $this->option( 'single_webcomic_link' ) ) {
										case 'previous' : $a = ' selected'; break;
										default         : $b = ' selected';
									}
									
									$s = '
									<select name="single_webcomic_link">
										<option value="next"' . $b . '>' . __( 'next', 'inkblot' ) . '</option>
										<option value="previous"' . $a . '>' . __( 'previous', 'inkblot' ) . '</option>
									</select>'; unset( $a, $b );
									
									printf( __( '<label>Link webcomic images to the %s webcomic</label>', 'inkblot' ), $s );
								?>
							</p>
							<p>
								<input type="checkbox" name="archive_webcomic_toggle" value="1"<?php if ( $this->option( 'archive_webcomic_toggle' ) ) echo ' checked'; ?>>
								<label>
								<?php
									$a = $b = $c = $d = '';
									
									switch ( $this->option( 'archive_webcomic_size' ) ) {
										case 'small' : $a = ' selected'; break;
										case 'medium': $b = ' selected'; break;
										case 'large' : $c = ' selected'; break;
										default      : $d = ' selected';
									}
									
									$s = '
									<select name="archive_webcomic_size">
										<option value="full"' . $d . '>' . __( 'full', 'inkblot' ) . '</option>
										<option value="large"' . $c . '>' . __( 'large', 'inkblot' ) . '</option>
										<option value="medium"' . $b . '>' . __( 'medium', 'inkblot' ) . '</option>
										<option value="small"' . $a . '>' . __( 'small', 'inkblot' ) . '</option>
									</select>'; unset( $a, $b, $c, $d );
									
									printf( __( 'Show %s webcomic previews on archive and search pages', 'inkblot' ), $s );
								?>
								</label>
							</p>
							<p>
								<input type="checkbox" name="embed_webcomic_toggle" value="1"<?php if ( $this->option( 'embed_webcomic_toggle' ) ) echo ' checked'; ?>>
								<?php
									$a = $b = $c = $d = '';
									
									switch ( $this->option( 'embed_webcomic_size' ) ) {
										case 'small' : $a = ' selected'; break;
										case 'medium': $b = ' selected'; break;
										case 'large' : $c = ' selected'; break;
										default      : $d = ' selected';
									}
									
									$s = '
									<select name="embed_webcomic_size">
										<option value="full"' . $d . '>' . __( 'full', 'inkblot' ) . '</option>
										<option value="large"' . $c . '>' . __( 'large', 'inkblot' ) . '</option>
										<option value="medium"' . $b . '>' . __( 'medium', 'inkblot' ) . '</option>
										<option value="small"' . $a . '>' . __( 'small', 'inkblot' ) . '</option>
									</select>'; unset( $a, $b, $c, $d );
									
									$e = $f = '';
									
									switch ( $this->option( 'embed_webcomic_format' ) ) {
										case 'sbbcode' : $e = ' selected'; break;
										default        : $f = ' selected';
									}
									
									$t = '
									<select name="embed_webcomic_format">
										<option value="shtml"' . $f . '>' . __( 'html', 'inkblot' ) . '</option>
										<option value="sbbcode"' . $e . '>' . __( 'bbcode', 'inkblot' ) . '</option>
									</select>'; unset( $e, $f );
									
									printf( __( '<label>Show embed code with %s previews</label><label> in %s format on single webcomic pages</label>', 'inkblot' ), $s, $t );
								?>
							</p>
							<p><label><input type="checkbox" name="prints_original_toggle" value="1"<?php if ( $this->option( 'prints_original_toggle' ) ) echo ' checked'; ?>> <?php _e( 'Sell original prints', 'inkblot' ); ?></label></p>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'inkblot' ); ?>">
					<input type="hidden" name="action" value="inkblot_settings">
				</p>
			</form>
		</div>
		<script type="text/javascript">
			jQuery( document ) . ready( function( $ ) {
				$( 'input[name=layout],input[name=dim_webcomic]' ) . change( function() {
					var webcomic, content, sidebar1, sidebar2, site, columns, layout;
					
					webcomic = Math.floor( Number( $( 'input[name=dim_webcomic]' ) . attr( 'value' ) ) );
					columns  = Number( $( 'input[name=layout]:checked' ) . attr( 'value' ) . substring( 0, 1 ) );
					layout   = Number( $( 'input[name=layout]:checked' ) . attr( 'value' ) . substring( 2, 4 ) );
					
					if ( 2 < columns ) {
						if ( 1 == layout || 2 == layout || 3 == layout ) {
							site     = webcomic;
							content  = site * .5;
							sidebar1 = sidebar2 = Math.round( ( content / 2 ) );
						} else if ( 4 == layout || 5 == layout || 6 == layout ) {
							site     = webcomic * 2;
							content  = webcomic;
							sidebar1 = sidebar2 = Math.round( ( content / 2 ) );
						} else {
							site     = webcomic + Math.floor( webcomic * .38 );
							content  = Math.floor( webcomic * .62 );
							sidebar1 = sidebar2 = Math.floor( webcomic * .38 );
						}
					} else if ( 1 < columns ) {
						if ( 3 == layout || 4 == layout ) {
							site     = Math.floor( webcomic * 1.62 );
							content  = webcomic;
						} else {
							site     = webcomic;
							content  = site - Math.round( site * .38 );
						}
						
						sidebar1 = site - content;
						sidebar2 = 0;
					} else {
						site     = webcomic;
						content  = webcomic;
						sidebar1 = sidebar2 = 0;
					}
					
					$( 'input[name=dim_content]' ) . attr( 'value', content );
					$( 'input[name=dim_sidebar1]' ) . attr( 'value', sidebar1 );
					$( 'input[name=dim_sidebar2]' ) . attr( 'value', sidebar2 );
					$( 'input[name=dim_site],input[name=header_w]' ) . attr( 'value', site ); } );
				});
			</script>
		<?php
	}
	
	function hook_save_post( $id, $post ) {
		if ( empty( $_REQUEST[ 'original_publish' ] ) || wp_is_post_autosave( $id ) || wp_is_post_revision( $id ) )
			return false;
		
		if ( 'webcomic_archive.php' == $post->page_template || 'webcomic_home.php' == $post->page_template ) {
			$post_meta = array();
			
			$post_meta[ 'webcomic_collection' ] = intval( $_REQUEST[ 'webcomic_collection' ] );
			$post_meta[ 'webcomic_order' ]      = ( !empty( $_REQUEST[ 'webcomic_order' ] ) ) ? $_REQUEST[ 'webcomic_order' ] : null;
			$post_meta[ 'webcomic_group' ]      = ( !empty( $_REQUEST[ 'webcomic_group' ] ) ) ? $_REQUEST[ 'webcomic_group' ] : null;
			$post_meta[ 'webcomic_image' ]      = ( !empty( $_REQUEST[ 'webcomic_image' ] ) ) ? $_REQUEST[ 'webcomic_image' ] : null;
			
			update_post_meta( $id, 'inkblot', $post_meta );
		} else
			delete_post_meta( $id, 'inkblot' );
	}
	
	function admin_metabox( $post ) {
		$post_meta = current( get_post_meta( $post->ID, 'inkblot' ) );
	?>
	<div class="archive-controls">
		<?php
			$a = $b = '';
			
			switch ( $post_meta[ 'webcomic_group' ] ) {
				case 'storyline': $a = ' selected'; break;
				default         : $b = ' selected';
			}
			
			$s = '
			<select name="webcomic_group">
				<option value="month"' . $b . '>' . __( 'date', 'inkblot' ) . '</option>
				<option value="storyline"' . $a . '>' . __( 'storyline', 'inkblot' ) . '</option>
			</select>'; unset( $a, $b );
			
			$a = $b = $c = $d = '';
			
			switch ( $post_meta[ 'webcomic_image' ] ) {
				case 'full'  : $a = ' selected'; break;
				case 'large' : $b = ' selected'; break;
				case 'medium': $c = ' selected'; break;
				case 'small' : $d = ' selected'; break;
			}
			
			$u = '
			<select name="webcomic_image">
				<option value="">' . __( 'text links', 'inkblot' ) . '</option>
				<option value="small"' . $d . '>' . __( 'small images', 'inkblot' ) . '</option>
				<option value="medium"' . $c . '>' . __( 'medium images', 'inkblot' ) . '</option>
				<option value="large"' . $b . '>' . __( 'large images', 'inkblot' ) . '</option>
				<option value="full"' . $a . '>' . __( 'full images', 'inkblot' ) . '</option>
			</select>'; unset( $a, $b, $c, $d );
			
			$walker   = new webcomic_Walker_AdminTermDropdown();
			$selected = array( $post_meta[ 'webcomic_collection' ] );
		
			$t = '<select name="webcomic_collection" class="archive"><option value="0">' . __( 'any collection', 'inkblot' ) . '</option>' . $walker->walk( get_terms( 'webcomic_collection', 'get=all' ), 0, array( 'selected' => $selected ) ) . '</select>';
			
			printf( __( 'Show webcomics grouped by %s from %s as %s on this page', 'inkblot' ), $s, $t, $u );
			
			unset( $s, $t, $u );
		?>
	</div>
	<div class="home-controls">
		<?php
			$a = $b = '';
			
			switch ( $post_meta[ 'webcomic_order' ] ) {
				case 'DESC' : $a = ' selected'; break;
				default     : $b = ' selected';
			}
			
			$s = '
			<select name="webcomic_order">
				<option value="ASC"' . $b . '>' . __( 'first', 'inkblot' ) . '</option>
				<option value="DESC"' . $a . '>' . __( 'last', 'inkblot' ) . '</option>
			</select>'; unset( $a, $b );
			
			$walker   = new webcomic_Walker_AdminTermDropdown();
			$selected = array( $post_meta[ 'webcomic_collection' ] );
		
			$t = '<select name="webcomic_collection" class="home"><option value="0">' . __( 'any collection', 'inkblot' ) . '</option>' . $walker->walk( get_terms( 'webcomic_collection', 'get=all' ), 0, array( 'selected' => $selected ) ) . '</select>';
			
			printf( __( 'Show the %s webcomic from %s on this page', 'inkblot' ), $s, $t );
		?>
	</div>
	<div class="wrongtemplate"><?php _e( 'Select the <em>Webcomic Archive</em> template from the <strong>Page Attributes</strong> metabox to create a webcomic archive page, or the <em>Webcomic Home</em> template to create a webcomic "home" page.', 'inkblot' ); ?></div>
	<script>
		if ( 'webcomic_archive.php' == jQuery( '#page_template' ) . val() )
			jQuery( '#inkblot .wrongtemplate,#inkblot .home-controls' ) . hide();
		else if ( 'webcomic_home.php' == jQuery( '#page_template' ) . val() )
			jQuery( '#inkblot .wrongtemplate,#inkblot .archive-controls' ) . hide();
		else
			jQuery( '#inkblot .archive-controls,#inkblot .home-controls' ) . hide();
		
		jQuery( '#page_template' ) . change(
			function() {
				if ( 'webcomic_archive.php' == jQuery( '#page_template' ) . val() )
					jQuery( '#inkblot .wrongtemplate,#inkblot .home-controls' ) . fadeOut('fast',function(){jQuery('#inkblot .archive-controls').fadeIn('fast')});
				else if ( 'webcomic_home.php' == jQuery( '#page_template' ) . val() )
					jQuery( '#inkblot .wrongtemplate,#inkblot .archive-controls' ) . fadeOut('fast',function(){jQuery('#inkblot .home-controls').fadeIn('fast')});
				else
					jQuery( '#inkblot .archive-controls,#inkblot .home-controls' ) . fadeOut('fast',function(){jQuery('#inkblot .wrongtemplate').fadeIn('fast')});
			}
		);
		
		jQuery( 'select[name=webcomic_collection]' ) . change(
			function() {
				if ( 'archive' == jQuery( this ) . attr( 'class' ) )
					jQuery( 'select[name=webcomic_collection].home' ) . val( jQuery( this ) . val() );
				else
					jQuery( 'select[name=webcomic_collection].archive' ) . val( jQuery( this ) . val() );
			}
		);
	</script>
	<?php
	}
} global $inkblot; $inkblot = new inkblot(); //Initialize the theme

/** Displays post comments */
class inkblot_Walker_Comment extends Walker {
	var $tree_type = 'comment';
	var $db_fields = array ( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
	
	function start_lvl( &$output, $depth, $args ) {
		$GLOBALS[ 'comment_depth' ] = $depth++;
	}
	
	function end_lvl( &$output, $depth, $args ) {
		$GLOBALS[ 'comment_depth' ] = $depth++;
	}
	
	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		if ( !$element )
			return false;
		
		$id_field = $this->db_fields[ 'id' ];
		$id       = $element->$id_field;
		
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		
		if ( $max_depth <= $depth + 1 && isset( $children_elements[ $id ] ) ) {
			foreach ( $children_elements[ $id ] as $child )
				$this->display_element( $child, $children_elements, $max_depth, $depth, $args, $output );
			
			unset( $children_elements[ $id ] );
		}
	}
	
	function start_el( &$output, $comment, $depth, $args ) {
		$depth++;
		
		$GLOBALS[ 'comment' ]       = $comment;
		$GLOBALS[ 'comment_depth' ] = $depth;
		
		extract( $args, EXTR_SKIP );
		
		if ( !$comment->comment_type ) {
		?>
		<article id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<footer class="comment-foot">
				<?php 
					if ( !empty( $args[ 'avatar_size' ] ) )
						echo get_avatar( $comment, $args[ 'avatar_size' ] );
					
					printf( '<b class="author">%s</b>', get_comment_author_link() );
				?>
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate><?php printf( '%s @ %s', get_comment_date(),  get_comment_time() ); ?></time></a>
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'comment-clear', 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ) ) ); ?>
				<?php edit_comment_link(); ?>
			</footer>
		<?php
			if ( empty( $comment->comment_approved ) )
				echo '<p class="pending">' . __( 'Your comment is awaiting moderation.', 'inkblot' ) . '</p>';
			
			comment_text();
		?>
		<hr id="comment-clear-<?php comment_ID(); ?>">
		<?php } else { ?>
		<article id="pingback-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<footer class="pingback-foot">
				<b><?php comment_type(); ?></b>
				<small><?php comment_author_link(); edit_comment_link ( __( 'edit', 'inkblot') ); ?></small>
			</footer>
			<p><?php comment_author_link();?></p>
		<?php
		}
	}
	
	function end_el( &$output, $comment, $depth, $args ) {
		echo '</article>';
	}
}
?>