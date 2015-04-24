<?php
/**
 * Template Name: Webcomic Homepage
 * 
 * Useful for creating a dedicated home page for specific Webcomic collections.
 * 
 * @package Inkblot
 */

if ( ! webcomic()) {
	get_template_part('page');
	
	return;
}

get_header();

$webcomic_collection = get_webcomic_collection();
$webcomic_order = get_post_meta(get_the_ID(), 'inkblot_webcomic_order', true);
$webcomics = new WP_Query(array(
	'posts_per_page' => 1,
	'post_type' => $webcomic_collection ? $webcomic_collection : get_webcomic_collections(),
	'order' => $webcomic_order ? $webcomic_order : 'DESC'
));
?>

<?php
	if ( ! get_theme_mod('webcomic_content', false) and $webcomics->have_posts()) :
		while ($webcomics->have_posts()) : $webcomics->the_post();
			get_template_part('webcomic/display', get_post_type());
		endwhile;
		
		$webcomics->rewind_posts();
	endif;
?>

<main role="main">
	
	<?php
		if ($webcomics and get_theme_mod('webcomic_home_order', 'DESC') and $webcomics->have_posts()) :
			while ($webcomics->have_posts()) : $webcomics->the_post();
				if (get_theme_mod('webcomic_content', false)) :
					get_template_part('webcomic/display', get_post_type());
				endif;
				
				get_template_part('webcomic/content', get_post_type());
			endwhile;
			
			if (get_post_meta(get_the_ID(), 'inkblot_webcomic_transcripts', true)) :
				webcomic_transcripts_template();
			endif;
			
			if (get_post_meta(get_the_ID(), 'inkblot_webcomic_comments', true)) :
				comments_template();
			endif;
		endif;
	?>
	
</main>

<?php get_sidebar(); get_footer();