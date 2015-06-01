<?php
/**
 * Contains template tags specific to the Inkblot theme.
 * 
 * @package Inkblot
 */

if ( ! function_exists('webcomic')) :
/**
 * Is a compatible version of Webcomic installed and active?
 * 
 * This function is actually part of the Webcomic plugin. If it doesn't exist,
 * it's probably safe to assume that Webcomic is not installed and active.
 * 
 * @return boolean
 */
function webcomic() {
	return false;
}
endif;

if ( ! function_exists('inkblot_page_description')) :
/**
 * Return appropriate `<meta>` description text.
 * 
 * @return string
 */
function inkblot_page_description() {
	$object = get_queried_object();
	$description = get_bloginfo('description', 'display');
	
	if (is_singular() and has_excerpt() and ! is_home()) {
		$description = get_the_excerpt();
	} else if ((is_category() or is_tag() or is_tax()) and $object->description) {
		$description = $object->description;
	} elseif (is_author() and $bio = get_user_meta($object->ID, 'description', true)) {
		$description = $bio;
	}
	
	$description = strip_tags($description);
	
	if (140 < strlen($description)) {
		$description = substr($description, 0, 133) . '&#8230;';
	}
	
	return esc_attr($description);
}
endif;

if ( ! function_exists('inkblot_date_archive_title')) :
/**
 * Return the date archive title, formatted for Inkblot.
 * 
 * @return string
 */
function inkblot_date_archive_title($year = 'Y', $month = 'F Y', $date = '') {
	if (is_year()) {
		$date = $year;
	} else if (is_month()) {
		$date = $month;
	}
	
	return get_the_date($date);
}
endif;

if ( ! function_exists('inkblot_post_nav')) :
/**
 * Return post navigation.
 * 
 * @deprecated
 * @param mixed $class CSS classes or an array of classes to add to the <nav> element.
 * @param string $previous Previous post link text.
 * @param string $next Next post link text.
 * @param boolean $in_same_cat Whether the previous and next post must be within the same category as the current post.
 * @param string $excluded_categories List of categories to exclude for previous and next posts, like '1 and 2 and 3'.
 * @return string
 */
function inkblot_post_nav($class = '', $previous = '&laquo; %title', $next = '%title &raquo;', $in_same_cat = false, $excluded_categories = '') {
	if (get_adjacent_post(false, '', true) or get_adjacent_post(false, '', false)) {
		ob_start();
		
		previous_post_link('%link', $previous, $in_same_cat, $excluded_categories);
		next_post_link('%link', $next, $in_same_cat, $excluded_categories);
		
		$links = ob_get_contents();
		
		ob_end_clean();
		
		return sprintf('<nav role="navigation" class="%s">%s</nav>',
			implode(' ', array_merge(array('posts'), (array) $class)),
			$links
		);
	}
}
endif;

if ( ! function_exists('inkblot_posts_nav')) :
/**
 * Return posts paged navigation.
 * 
 * @param array $args Arguments to pass to either `paginate_links` or `get_posts_nav_link`.
 * @param boolean $paged Whether to display paged navigation.
 * @return string
 */
function inkblot_posts_nav($args = array(), $paged = false) {
	return $paged
		? get_the_posts_pagination(array_merge(array(
			'prev_text' => __('&laquo; Previous Page', 'inkblot'),
			'next_text' => __('Next Page &raquo;', 'inkblot'),
			'before_page_number' => sprintf('<span class="screen-reader-text">%s </span>', __('Page', 'inkblot'))
		), (array) $args))
		: get_the_posts_navigation(array_merge(array(
			'prev_text' => __('&laquo; Previous Page', 'inkblot'),
			'next_text' => __('Next Page &raquo;', 'inkblot')
		), (array) $args));
}
endif;

if ( ! function_exists('inkblot_post_datetime')) :
/**
 * Return the post publish date and time, formatted for Inkblot.
 * 
 * @return string
 */
function inkblot_post_datetime() {
	return sprintf('<a href="%1$s" rel="bookmark"><span class="screen-reader-text">%2$s </span><time datetime="%3$s">%4$s</time></a>',
		esc_url(get_permalink()),
		sprintf(__('%1$s published on', 'inkblot'), get_the_title()),
		esc_attr(get_the_date('c')),
		esc_html(get_the_date())
	);
}
endif;

if ( ! function_exists('inkblot_post_author')) :
/**
 * Return a post author link, formatted for Inkblot.
 * 
 * @return string
 */
function inkblot_post_author() {
	return sprintf('<a href="%1$s" rel="author"><span class="screen-reader-text">%2$s </span>%3$s</a>',
		get_author_posts_url(get_the_author_meta('ID')),
		sprintf(__('Read more posts by the author of %1$s,', 'inkblot'), get_the_title()),
		get_the_author()
	);
}
endif;

if ( ! function_exists('inkblot_post_parent')) :
/**
 * Return a post parent link, formatted for Inkblot.
 * 
 * @return string
 */
function inkblot_post_parent() {
	if ($ancestors = get_post_ancestors(get_the_ID())) {
		$parent = current($ancestors);
		
		return sprintf('<a href="%1$s" rel="gallery"><span class="screen-reader-text">%2$s </span>%3$s</a>',
			esc_url(get_permalink($parent)),
			__('Return to', 'inkblot'),
			get_the_title($parent)
		);
	}
}
endif;

if ( ! function_exists('inkblot_image_details')) :
/**
 * Return image information for attachments, formatted for Inkblot.
 * 
 * @return string
 */
function inkblot_image_details() {
	if ($data = wp_get_attachment_metadata() and isset($data['width'], $data['height'])) {
		return sprintf('<a href="%1$s" class="image"><span class="screen-reader-text">%2$s </span>%3$s &#215; %4$s</a>',
			esc_url(wp_get_attachment_url()),
			__('View image at full size,', 'inkblot'),
			$data['width'],
			$data['height']
		);
	}
}
endif;

if ( ! function_exists('inkblot_start_comment')) :
/**
 * Render a comment.
 * 
 * @param object $comment Comment data object.
 * @param array $args Arguments passed to `wp_list_comments`.
 * @param integer $depth Depth of comment in reference to parents.
 */
function inkblot_start_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	
	<article id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<footer class="comment-footer">
			
			<?php
				print empty($args['avatar_size']) ? '' : get_avatar($comment, $args['avatar_size']);
				
				comment_author_link();
				
				printf(__('<a href="%1$s" class="time"><span class="screen-reader-text">%2$s </span><time datetime="%3$s">%4$s @ %5$s</time></a>', 'inkblot'),
					esc_url(get_comment_link($comment->comment_ID)),
					sprintf(__('Comment by %s published on', 'inkblot'), get_comment_author()),
					get_comment_time('c'),
					get_comment_date(),
					get_comment_time()
				);
				
				comment_reply_link(array_merge($args, array(
					'depth' => $depth,
					'max_depth' => $args['max_depth']
				)));
				
				edit_comment_link(sprintf(__('Edit<span class="screen-reader-text"> comment by %s</span>', 'inkblot'), get_comment_author()));
			?>
			
		</footer><!-- .comment-footer -->
		
		<?php if ( ! $comment->comment_approved) : ?>
			
			<div class="moderating-comment"><?php _e('Your comment is awaiting moderation.', 'inkblot'); ?></div><!-- .moderating-comment -->
			
		<?php endif; ?>
		
		<div class="comment-content"><?php comment_text(); ?></div><!-- .comment-content -->
		
	<?php
}
endif;

if ( ! function_exists('inkblot_end_comment')) :
/**
 * Render a comment closing tag.
 * 
 * @param object $comment Comment data object.
 * @param array $args Arguments passed to `wp_list_comments`.
 * @param integer $depth Depth of comment in reference to parents.
 */
function inkblot_end_comment($comment, $args, $depth) { ?>
	
	</article><!-- #comment-<?php comment_ID(); ?> -->
	
	<?php
}
endif;

if ( ! function_exists('inkblot_comments_nav')) :
/**
 * Return comments paged navigation.
 * 
 * @param mixed $paged Arguments to pass to `paginate_comments_link`, or true to enable pagination with default arguments.
 * @param string $previous Label to use for the previous comments page link when not using paged navigation.
 * @param string $next Label to use for the next comments page link when not using paged navigation.
 * @return string
 */
function inkblot_comments_nav($paged = array(), $previous = '', $next = '') {
	if (1 < get_comment_pages_count() and get_option('page_comments')) {
		return sprintf('<nav class="navigation %1$s" role="navigation" aria-label="%2$s">%3$s</nav>',
			$paged ? 'pagination' : 'comment-navigation',
			__('Comments navigation', 'inkblot'),
			$paged
				? paginate_comments_links(array_merge(array(
					'echo' => false,
					'prev_text' => __('&laquo; Previous Page', 'inkblot'),
					'next_text' => __('Next Page &raquo;', 'inkblot'),
					'before_page_number' => sprintf('<span class="screen-reader-text">%s </span>', __('Page', 'inkblot'))
				), (array) $paged))
				: '<div class="nav-previous">' . get_previous_comments_link($previous) . '</div><div class="nav-next">' . get_next_comments_link($next) . '</div>'
		);
	}
}
endif;

if ( ! function_exists('inkblot_count_widgets')) :
/**
 * Return the number of widgets for the specified sidebar.
 * 
 * @param string $sidebar ID of the sidebar to count widgets for.
 * @param integer $default Default number of widgets for `$sidebar`.
 * @return integer
 */
function inkblot_count_widgets($sidebar, $default = 1) {
	$count = $default;
	$single = ! in_array($sidebar, array('primary-sidebar', 'secondary-sidebar'));
	$sidebar = "sidebar-{$sidebar}";
	$sidebars = get_option('sidebars_widgets');
	
	if (isset($sidebars[$sidebar]) and count($sidebars[$sidebar]) and get_theme_mod("{$sidebar}-columns", $single)) {
		$count = 10 < count($sidebars[$sidebar]) ? 10 : count($sidebars[$sidebar]);
	}
	
	return $count;
}
endif;

if ( ! function_exists('inkblot_widgetized')) :
/**
 * Return a generic widgetized area.
 * 
 * @param string $id ID of the widgetized area.
 * @param string $class Space-separated string of classes to append to the container.
 * @return string
 * @uses inkblot_count_widgets()
 */
function inkblot_widgetized($id, $class = '') {
	$widget = '';
	$sidebars = require get_template_directory() . '/-/php/sidebars.php';
	
	if ($count = inkblot_count_widgets($id, 0) or is_customize_preview()) :
		$columns = "columns-{$count}";
		
		ob_start(); ?>
			
			<div class="widgets <?php print $id . ' ' . $columns . ' ' . $class; ?>">
				<h1 class="screen-reader-text"><?php print $sidebars[$id][0]; ?></h1>
				
				<?php dynamic_sidebar($id); ?>
				
			</div><!-- #<?php print $id; ?> -->
			
		<?php
		
		$widget = ob_get_contents();
		
		ob_end_clean();
	endif;
	
	return $widget;
}
endif;

if ( ! function_exists('inkblot_search_id')) :
/**
 * Return a unique search form ID.
 * 
 * @param boolean $add Increment the counter.
 * @return string
 */
function inkblot_search_id($add = true) {
	static $count = 0;
	
	if ($add) {
		$count++;
	}
	
	return "s{$count}";
}

endif;

if ( ! function_exists('inkblot_copyright')) :
/**
 * Return copyright notice.
 * 
 * @param integer $user User ID to use for the copyright attribution name.
 * @return string
 */
function inkblot_copyright($user = 0) {
	global $wpdb;
	
	$end = date('Y');
	$start = $wpdb->get_results("SELECT YEAR(min(post_date)) AS year FROM {$wpdb->posts} WHERE post_status = 'publish'");
	$author = get_userdata($user);
	
	return sprintf('&copy; %1$s %2$s',
		$start[0]->year === $end ? $end : $start[0]->year . '&ndash;' . $end,
		$author ? $author->display_name : get_bloginfo('name', 'display')
	);
}
endif;

if ( ! function_exists('inkblot_contributor')) :
/**
 * Return a contributor block for the contributor template.
 * 
 * @param integer $user User ID.
 * @param integer $avatar Size of the avatar to display.
 * @return string
 */
function inkblot_contributor($user, $avatar = 96) {
	ob_start(); ?>
	
	<aside role="complementary" class="contributor">
		
		<?php if ($avatar) : ?>
			
			<div class="contributor-image"><?php echo get_avatar($user, (int) $avatar); ?></div><!-- .contributor-image -->
			
		<?php endif; ?>
		
		<header class="contributor-header">
			
			<?php if ($post_count = count_user_posts($user)) : ?>
				
				<h2><a href="<?php print esc_url(get_author_posts_url($user)); ?>"><?php the_author_meta('display_name', $user); ?></a></h2>
				
			<?php else : ?>
				
				<h2><?php the_author_meta('display_name', $user); ?></h2>
				
			<?php endif; ?>
			
		</header><!-- .contributor-header -->
		
		<div class="contributor-description">
			
			<?php print wpautop(get_the_author_meta('description', $user)); ?>
			
		</div><!-- .contributor-description -->
		
	</aside><!-- .contributor -->
	<?php
	
	$contributor = ob_get_contents();
	
	ob_end_clean();
	
	return $contributor;
}
endif;

if ( ! function_exists('inkblot_webcomic_transcript')) :
/**
 * Render a Webcomic transcript.
 */
function inkblot_webcomic_transcript() { ?>
	<article id="webcomic_transcript-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-content"><?php the_content(); ?></div><!-- .post-content -->
		<footer class="post-footer">
			
			<?php
				the_webcomic_transcript_authors(true, sprintf('<span class="webcomic-transcript-authors"><span class="screen-reader-text">%s</span>', __('Webcomic Transcript Authors', 'inkblot')), ', ', '</span>');
				
				the_webcomic_transcript_languages(sprintf('<span class="webcomic-transcript-languages"><span class="screen-reader-text">%s</span>', __('Webcomic Transcript Languages', 'inkblot')), ', ', '</span>');
			?>
			
		</footer><!-- .post-footer -->
	</article><!-- #webcomic-transcript-<?php the_ID(); ?> -->
	<?php
}
endif;