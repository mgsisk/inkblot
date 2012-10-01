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
		global $wp_query;
		
		if ( is_singular() and !is_home() ) {
			if ( post_password_required( $wp_query->get_queried_object() ) ) {
				$output = __( 'There is no description because this is a protected post.', 'inkblot' );
			} else if ( !$output = $wp_query->get_queried_object()->post_excerpt ) {
				$output = apply_filters( 'wp_trim_excerpt', wp_trim_words( str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', strip_shortcodes( $wp_query->get_queried_object()->post_content ) ) ), apply_filters( 'excerpt_length', 55 ), apply_filters( 'excerpt_more', ' [...]' ) ) );
			}
		} else if ( is_category() or is_tag() or is_tax() or is_author() ) {
			$output = is_author() ? get_user_meta( $wp_query->get_queried_object()->data->ID, 'description', true ) : $wp_query->get_queried_object()->description;
		} else {
			$output = get_bloginfo( 'description', 'display' );
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
		
		$edit = $media = $parent = $categories = $tags = $collection = $storylines = $characters = $transcribe = $purchase = '';
		$date = sprintf( __( '<a href="%1$s" title="%2$s" rel="bookmark"><time datetime="%3$s">%4$s</time></a>', 'inkblot' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
		$author = sprintf( __( '<a href="%1$s" rel="author">%2$s</a>', 'inkblot' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
		
		
		if ( current_user_can( 'edit_post' ) ) {
			$edit = sprintf( __( '<a href="%s" class="post-edit-link">Edit This</a>', 'inkblot' ), get_edit_post_link() );
		}
		
		if ( webcomic() and is_a_webcomic() ) {
			if ( webcomic_transcripts_open() ) {
				$transcribe = WebcomicTag::webcomic_transcripts_link( '%link' );
			}
			
			if ( webcomic_prints_available() ) {
				$purchase = WebcomicTag::purchase_webcomic_link( '%link', __( 'Purchase', 'inkblot' ) );
			}
			
			$collection = WebcomicTag::webcomic_collection_link( '%link', '%title' );
			$storylines = WebcomicTag::get_the_webcomic_term_list( 0, 'sotyrline' );
			$characters = WebcomicTag::get_the_webcomic_term_list( 0, 'character' );
			
			if ( $storylines and $characters ) {
				$meta = __( 'Published in %8$s as part of %9$s featuring %10$s on %3$s by %4$s<span class="post-actions">%11$s%12$s%7$s</span>', 'inkblot' );
			} else if ( $storylines ) {
				$meta = __( 'Published in %8$s as part of %9$s on %3$s by %4$s<span class="post-actions">%11$s%12$s%7$s</span>', 'inkblot' );
			} else if ( $characters ) {
				$meta = __( 'Published in %8$s featuring %10$s on %3$s by %4$s<span class="post-actions">%11$s%12$s%7$s</span>', 'inkblot' );
			} else {
				$meta = __( 'Published in %8$s on %3$s by %4$s<span class="post-actions">%11$s%12$s%7$s</span>', 'inkblot' );
			}
		} else if ( is_attachment() ) {
			$data  = wp_get_attachment_metadata();
			$media = sprintf( __( '<a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a>', 'inkblot' ),
				esc_url( wp_get_attachment_url() ),
				$data[ 'width' ],
				$data[ 'height' ]
			);
			$parent = sprintf( __( '<a href="%1$s" title="Return to %2$s" rel="gallery">%3$s</a>', 'inkblot' ),
				esc_url( get_permalink( $post->post_parent ) ),
				esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
				get_the_title( $post->post_parent )
			);
			
			$meta = __( 'Published in %5$s at %6$s on %3$s<span class="post-actions">%7$s</span>', 'inkblot' );
		} else {
			$tags = get_the_tag_list( '', __( ', ', 'inkblot' ) );
			$categories = get_the_category_list( __( ', ', 'inkblot' ) );
			
			if ( $tags ) {
				$meta = __( 'Published in %1$s and tagged %2$s on %3$s by %4$s<span class="post-actions">%7$s</span>', 'inkblot' );
			} else if ( $categories ) {
				$meta = __( 'Published in %1$s on %3$s by %4$s<span class="post-actions">%7$s</span>', 'inkblot' );
			} else {
				$meta = __( 'Published on %3$s by %4$s<span class="post-actions">%7$s</span>', 'inkblot' );
			}
		} 
		
		return sprintf( $meta, $categories, $tags, $date, $author, $parent, $media, $edit, $collection, $storylines, $characters, $transcribe, $purchase );
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