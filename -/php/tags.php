<?php
/** Contains the InkblotTag class and template tag functions.
 * 
 * @package Inkblot
 */

/** Handle custom template tag functionality.
 * 
 * @package Inkblot
 */
class InkblotTag extends Inkblot {
	/** Override the parent constructor.
	 */
	public function __construct(){}
	
	/** Return appropriate `<meta>` description text.
	 * 
	 * @return string
	 */
	public function inkblot_page_description() {
		$object = get_queried_object();
		$output = get_bloginfo( 'description', 'display' );
		
		if ( is_singular() and has_excerpt() and !is_home() ) {
			$output = get_the_excerpt();
		} else if ( ( is_category() or is_tag() or is_tax() ) and $object->description ) {
			$output = $object->description;
		} elseif ( is_author() and $bio = get_user_meta( $object->ID, 'description', true ) ) {
			$output = $bio;
		}
		
		$output = 140 < strlen( $output = strip_tags( $output ) ) ? substr( $output, 0, 132 ) . '&hellip;' : $output;
		
		return esc_attr( $output );
	}
	
	/** Return posts paged navigation.
	 * 
	 * @param mixed $class CSS classes or an array of classes to add to the <nav> element.
	 * @param array $args An array of arguments to pass to either `paginate_links` or `get_posts_nav_link`.
	 * @param boolean $paged Whether to display paged navigation.
	 * @return string
	 */
	public static function inkblot_posts_nav( $class = '', $args = array(), $paged = false ) {
		global $wp_query;
	
		if ( $wp_query->max_num_pages > 1 ) {
			$class = array_merge( array( 'posts-paged' ), ( array ) $class );
			
			return sprintf( '<nav class="%s">%s</nav><!-- .posts-paged -->',
				join( ' ', ( array ) $class ),
				$paged ? preg_replace( '/>...</', '>&hellip;<', paginate_links( array_merge( array(
					'base'    => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
					'total'   => $wp_query->max_num_pages,
					'format'  => '?paged=%#%',
					'current' => max( 1, get_query_var( 'paged' ) )
				), ( array ) $args ) ) ) : get_posts_nav_link( ( array ) $args )
			);
		}
	}
	
	/** Return comments paged navigation.
	 * 
	 * @param mixed $class CSS classes or an array of classes to add to the <nav> element.
	 * @param mixed $paged An array of arguments to pass to `paginate_comments_link`, or true to enable pagination with default arguments.
	 * @param string $previous Label to use for the previous comments page link when not using `paginate_comments_link`.
	 * @param string $next Label to use for the next comments page link when not using `paginate_comments_link`.
	 * @return string
	 */
	public static function inkblot_comments_nav( $class = '', $paged = array(), $previous = '', $next = '' ) {
		if ( 1 < get_comment_pages_count() and get_option( 'page_comments' ) ) {
			$class = array_merge( array( 'comments-paged' ), ( array ) $class );
			
			return sprintf( '<nav class="%s">%s</nav><!-- .comments-paged -->',
				join( ' ', ( array ) $class ),
				$paged ? paginate_comments_links( array_merge( array(
					'echo' => false
				), ( array ) $paged ) ) : get_previous_comments_link( $previous ) . get_next_comments_link( $next )
			);
		}
	}
	
	/** Return post meta information.
	 * 
	 * @return string
	 * @uses WebcomicTag::webcomic_collection_link()
	 * @uses WebcomicTag::get_the_webcomic_term_list()
	 * @uses WebcomicTag::get_the_webcomic_term_list()
	 * @uses is_a_webcomic()
	 * @uses webcomic_prints_available()
	 */
	public static function inkblot_post_meta() {
		global $post;
		
		$date = sprintf( __( ' on <a href="%1$s" title="%2$s" rel="bookmark"><time datetime="%3$s">%4$s</time></a>', 'inkblot' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
		
		$author = sprintf( __( ' by <a href="%1$s" rel="author">%2$s</a>', 'inkblot' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
		
		$edit = current_user_can( 'edit_post', $post->ID ) ? sprintf( __( '<a href="%s" class="post-edit-link">Edit This</a>', 'inkblot' ), get_edit_post_link() ) : '';
		
		if ( webcomic() and is_a_webcomic() ) {
			$meta = sprintf( __( 'Published%1$s%2$s%3$s%4$s%5$s<span class="post-actions">%6$s%7$s%8$s</span>', 'inkblot' ),
				WebcomicTag::get_the_webcomic_collection_list( 0, __( ' in ', 'inkblot' ) ),
				WebcomicTag::get_the_webcomic_term_list( 0, 'storyline', __( ' as part of ', 'inkblot' ) ),
				WebcomicTag::get_the_webcomic_term_list( 0, 'character', __( ' featuring ', 'inkblot' ) ),
				$date,
				$author,
				webcomic_transcripts_open() ? WebcomicTag::webcomic_transcripts_link( '%link' ) : '',
				webcomic_prints_available() ? WebcomicTag::purchase_webcomic_link( '%link', __( 'Purchase', 'inkblot' ) ) : '',
				$edit
			);
		} else if ( is_attachment() ) {
			$data = wp_get_attachment_metadata();
			$meta = sprintf( __( 'Published%1$s%2$s%3$s%4$s<span class="post-actions">%5$s</span>', 'inkblot' ),
				$post->post_parent ? sprintf( __( ' in <a href="%1$s" title="Return to %2$s" rel="gallery">%3$s</a>', 'inkblot' ),
					esc_url( get_permalink( $post->post_parent ) ),
					esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
					get_the_title( $post->post_parent )
				) : '',
				isset( $data[ 'width' ], $data[ 'height' ] ) ? sprintf( __( ' at <a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a>', 'inkblot' ),
					esc_url( wp_get_attachment_url() ),
					$data[ 'width' ],
					$data[ 'height' ]
				) : '',
				$date,
				$author,
				$edit
			);
		} else {
			$meta = sprintf( __( 'Published%1$s%2$s%3$s%4$s<span class="post-actions">%5$s</span>', 'inkblot' ),
				get_the_term_list( $post->ID, 'category', __( ' in ', 'inkblot' ), __( ', ', 'inkblot' ) ),
				get_the_tag_list( __( ' and tagged ', 'inkblot' ), __( ', ', 'inkblot' ) ),
				$date,
				$author,
				$edit
			);
		} 
		
		return $meta;
	}
	
	/** Return a unique search form ID.
	 * 
	 * @param boolean $add Increment the counter.
	 * @return string
	 */
	public static function inkblot_search_id( $add = true ) {
		static $count = 0;
		
		if ( $add ) {
			$count++;
		}
		
		return "s{$count}";
	}
	
	/** Return copyright notice.
	  * 
	 * @param integer $user User ID to use for the copyright attribution name.
	 * @return string
	 */
	public static function inkblot_copyright( $user = 0 ) {
		global $wpdb;
		
		$end    = date( 'Y' );
		$start  = $wpdb->get_results( "SELECT YEAR( min( post_date ) ) AS year FROM {$wpdb->posts} WHERE post_status = 'publish'" );
		$output = sprintf( '&copy; %1$s %2$s',
			$start[ 0 ]->year === $end ? $end : $start[ 0 ]->year . '&ndash;' . $end,
			( $author = get_userdata( $user ) ) ? ' ' . $author->display_name : get_bloginfo( 'name', 'display' )
		);
		
		return $output;
	}
	
	/** Render a comment.
	 * 
	 * @param object $comment Comment data object.
	 * @param array $args Array of arguments passed to `wp_list_comments`.
	 * @param integer $depth Depth of comment in reference to parents.
	 */
	public static function inkblot_start_comment( $comment, $args, $depth ) {
		$GLOBALS[ 'comment' ] = $comment;
		?>
		<article id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<footer class="comment-footer">
				<?php
					printf( __( '%1$s%2$s on <a href="%3$s"><time datetime="%4$s">%5$s @ %6$s</time></a>', 'inkblot' ),
						$args[ 'avatar_size' ] ? get_avatar( $comment, $args[ 'avatar_size' ] ) : '',
						get_comment_author_link(),
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						get_comment_date(),
						get_comment_time()
					);
				?>
				<span class="comment-actions">
					<?php edit_comment_link(); ?>
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ) ) ); ?>
				</span>
			</footer><!-- .comment-footer -->
			<?php if ( !$comment->comment_approved ) : ?>
				<div class="moderating-comment"><?php _e( 'Your comment is awaiting moderation.', 'inkblot' ); ?></div><!-- .moderating-comment -->
			<?php endif; ?>
			<div class="comment-content"><?php comment_text(); ?></div><!-- .comment-content -->
		<?php
	}
	
	/** Render a comment closing tag.
	 * 
	 * @param object $comment Comment data object.
	 * @param array $args Array of arguments passed to `wp_list_comments`.
	 * @param integer $depth Depth of comment in reference to parents.
	 */
	public static function inkblot_end_comment( $comment, $args, $depth ) {
		?>
		</article><!-- #comment-<?php comment_ID(); ?> -->
		<?php
	}
	
	/** Is this a theme preview?
	 * 
	 * @return boolean
	 * @uses Inkblot::$preview
	 */
	public static function inkblot_theme_preview() {
		return self::$preview;
	}
}

if ( !function_exists( 'inkblot_page_description' ) ) {
	/** Render appropriate `<meta>` description text.
	 * 
	 * @uses InkblotTag::inkblot_page_description()
	 */
	function inkblot_page_description() {
		echo InkblotTag::inkblot_page_description();
	}
}

if ( !function_exists( 'inkblot_posts_nav' ) ) {
	/** Render posts page navigation.
	 * 
	 * @param mixed $class CSS classes or an array of classes to add to the <nav> element.
	 * @param array $args An array of arguments to pass to either `paginate_links` or `get_posts_nav_link`.
	 * @param boolean $paged Whether to display paged navigation.
	 * @uses InkblotTag::inkblot_posts_nav()
	 */
	function inkblot_posts_nav( $class = '', $args = array(), $paged = false ) {
		echo InkblotTag::inkblot_posts_nav( $class, $args, $paged );
	}
}

if ( !function_exists( 'inkblot_comments_nav' ) ) {
	/** Render comments paged navigation.
	 * 
	 * @param mixed $class CSS classes or an array of classes to add to the <nav> element.
	 * @param mixed $paged An array of arguments to pass to `paginate_comments_link`, or true to enable pagination with default arguments.
	 * @param string $previous Label to use for the previous comments page link when not using `paginate_comments_link`.
	 * @param string $next Label to use for the next comments page link when not using `paginate_comments_link`.
	 * @uses InkblotTag::inkblot_comments_nav()
	 */
	function inkblot_comments_nav( $class = '', $paged = array(), $previous = '', $next = '' ) {
		echo InkblotTag::inkblot_comments_nav( $class, $paged, $previous, $next );
	}
}

if ( !function_exists( 'inkblot_post_meta' ) ) {
	/** Render post meta information.
	 * 
	 * @uses InkblotTag::inkblot_post_meta()
	 */
	function inkblot_post_meta() {
		echo InkblotTag::inkblot_post_meta();
	}
}

if ( !function_exists( 'inkblot_search_id' ) ) {
	/** Render a unique search form ID.
	 * 
	 * @param boolean $add Increment the counter.
	 * @uses InkblotTag::inkblot_search_id()
	 */
	function inkblot_search_id( $add = true ) {
		echo InkblotTag::inkblot_search_id( $add );
	}
}
	
if ( !function_exists( 'inkblot_copyright' ) ) {
	/** Render copyright notice.
	 * 
	 * @param integer $user User ID to use for the copyright name.
	 * @uses InkblotTag::inkblot_copyright()
	 */
	function inkblot_copyright( $user = 0 ) {
		echo InkblotTag::inkblot_copyright( $user );
	}
}

if ( !function_exists( 'inkblot_start_comment' ) ) {
	/** Render a comment.
	 * 
	 * @param object $comment Comment data object.
	 * @param array $args Array of arguments passed to `wp_list_comments`.
	 * @param integer $depth Depth of comment in reference to parents.
	 * @uses InkblotTag::inkblot_start_comment()
	 */
	function inkblot_start_comment( $comment, $args, $depth ) {
		InkblotTag::inkblot_start_comment( $comment, $args, $depth );
	}
}

if ( !function_exists( 'inkblot_end_comment' ) ) {
	/** Render a comment closing tag.
	 * 
	 * @param object $comment Comment data object.
	 * @param array $args Array of arguments passed to `wp_list_comments`.
	 * @param integer $depth Depth of comment in reference to parents.
	 * @uses InkblotTag::inkblot_end_comment()
	 */
	function inkblot_end_comment( $comment, $args, $depth ) {
		InkblotTag::inkblot_end_comment( $comment, $args, $depth );
	}
}

if ( !function_exists( 'inkblot_theme_preview' ) ) {
	/** Is this a theme preview?
	 * 
	 * @return boolean
	 * @uses InkblotTag::inkblot_theme_preview()
	 */
	function inkblot_theme_preview() {
		return InkblotTag::inkblot_theme_preview();
	}
}
	
/** Is a compatible version of Webcomic installed?
 * 
 * This function is actually part of the Webcomic plugin. If it
 * doesn't exist, it's probably safe to always return false.
 * 
 * @return boolean
 */
if ( !function_exists( 'webcomic' ) ) {
	function webcomic() {
		return false;
	}
}

if ( !class_exists( 'Walker_InkblotNavMenu_Dropdown' ) ) {
	/** Handle responsive dropdown custom menu output.
	 * 
	 * @package Inkblot
	 */
	class Walker_InkblotNavMenu_Dropdown extends Walker_Nav_Menu {
		/** Override parent function. */
		function start_lvl( &$output, $depth ){}
		
		/** Override parent function. */
		function end_lvl( &$output, $depth ){}
	
		/** Start element output.
		 * 
		 * @param string $output Walker output string.
		 * @param object $item Current item being handled by the walker.
		 * @param integer $depth Depth the walker is currently at.
		 * @param array $args Arguments passed to the walker.
		 */
		function start_el( &$output, $item, $depth, $args ) {
			$classes     = empty( $item->classes ) ? array() : ( array ) $item->classes;
			$classes[]   = 'menu-item-' . $item->ID;
			$classes     = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			
			$item_output = sprintf( '<option value="%s"%s%s%s%s>%s%s%s%s',
				empty( $item->url ) ? '' : esc_attr( $item->url ),
				empty( $classes ) ? '' : sprintf( ' class="%s"', esc_attr( $classes ) ),
				empty( $item->attr_title ) ? '' : sprintf( ' title="%s"', esc_attr( $item->attr_title ) ),
				empty( $item->target ) ? '' : sprintf( ' data-target="%s"', esc_attr( $item->target ) ),
				false === strpos( $classes, 'current-menu-item' ) ? '' : ' selected',
				str_repeat( "&nbsp", $depth * 4 ),
				$args->link_before,
				apply_filters( 'the_title', $item->title, $item->ID ),
				$args->link_after
			);
			
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	
		/** End element output.
		 * 
		 * @param string $output Walker output string.
		 * @param object $item Current item being handled by the walker.
		 * @param integer $depth Depth the walker is currently at.
		 * @param array $args Arguments passed to the walker.
		 */
		function end_el( &$output, $item, $depth, $args ) {
			$output .= '</option>';
		}
	}
}

if ( !class_exists( 'Walker_InkblotPageMenu_Dropdown' ) ) {
	/** Handle responsive dropdown page menu output.
	 * 
	 * @package Inkblot
	 */
	class Walker_InkblotPageMenu_Dropdown extends Walker_PageDropdown {
		/** Override parent function. */
		function start_lvl( &$output, $depth ){}
		
		/** Override parent function. */
		function end_lvl( &$output, $depth ){}
	
		/** Start element output.
		 * 
		 * @param string $output Walker output string.
		 * @param object $page Current page being handled by the walker.
		 * @param integer $depth Depth the walker is currently at.
		 * @param array $args Arguments passed to the walker.
		 */
		function start_el( &$output, $page, $depth, $args, $current_page = 0 ) {
			if ( empty( $output ) ) {
				$output .= sprintf( '<option value="%s">%s%s%s',
					home_url( '/' ),
					$args[ 'link_before' ],
					__( 'Home', 'inkblot' ),
					$args[ 'link_after' ]
				);
			}
			
			$classes = array( 'page_item', "page-item-{$page->ID}" );
			
			if ( !empty( $current_page ) ) {
				$current_page_object = get_page( $current_page );
				
				if ( $ancestors = get_post_ancestors( $current_page_object->ID ) and in_array( $page->ID, ( array ) $ancestors ) ) {
					$classes[] = 'current_page_ancestor';
				}
				
				if ( $page->ID === $current_page ) {
					$classes[] = 'current_page_item';
				} elseif ( $current_page_object and $page->ID === $current_page_object->post_parent ) {
					$classes[] = 'current_page_parent';
				}
			} else if ( $page->ID === get_option( 'page_for_posts' ) ) {
				$classes[] = 'current_page_parent';
			}
			
			$classes = join( ' ', apply_filters( 'page_css_class', array_filter( $classes ), $page, $depth, $args, $current_page ) );
			
			if ( !empty( $args[ 'show_date' ] ) ) {
				$time = mysql2date( $args[ 'date_format' ], 'modified' === $args[ 'show_date' ] ? $page->post_modified : $page->post_date );
			} else {
				$time = '';
			}
			
			$output .= sprintf( '<option value="%s"%s%s>%s%s%s%s%s',
				get_permalink( $page->ID ),
				empty( $classes ) ? '' : sprintf( ' class="%s"', esc_attr( $classes ) ),
				false === strpos( $classes, 'current_page_item' ) ? '' : ' selected',
				str_repeat( "&nbsp", $depth * 4 ),
				$args[ 'link_before' ],
				apply_filters( 'the_title', $page->post_title, $page->ID ),
				$args[ 'link_after' ],
				$time
			);
		}
	
		/** End element output.
		 * 
		 * @param string $output Walker output string.
		 * @param object $page Current page being handled by the walker.
		 * @param integer $depth Depth the walker is currently at.
		 * @param array $args Arguments passed to the walker.
		 */
		function end_el( &$output, $item, $depth, $args ) {
			$output .= '</option>';
		}
	}
}