<?php
/**
 * Template Name: Webcomic Archive
 * 
 * Useful for creating a more traditional webcomic archive page.
 * 
 * @package Inkblot
 */

if ( ! webcomic()) :
	get_template_part('page');
	
	return;
endif;

get_header(); ?>

<main role="main">
	
	<?php
		while (have_posts()) : the_post();
			
			get_template_part('content', 'page');
			
			$webcomic_group = get_post_meta(get_the_ID(), 'inkblot_webcomic_group', true);
			$webcomic_arguments = array(
				'collection' => get_post_meta(get_the_ID(), 'webcomic_collection', true),
				'webcomics' => get_post_meta(get_the_ID(), 'inkblot_show_webcomics', true),
				'order' => get_post_meta(get_the_ID(), 'inkblot_webcomic_term_order', true),
				'target' => get_post_meta(get_the_ID(), 'inkblot_webcomic_term_target', true),
				'webcomic_order' => get_post_meta(get_the_ID(), 'inkblot_webcomic_order', true),
				'webcomic_image' => get_post_meta(get_the_ID(), 'inkblot_webcomic_image', true),
				'show_image' => get_post_meta(get_the_ID(), 'inkblot_webcomic_term_image', true)
			);
			
			if ('character' === $webcomic_group) :
				webcomic_list_characters($webcomic_arguments);
			elseif ('storyline' === $webcomic_group) :
				webcomic_list_storylines($webcomic_arguments);
			else :
				webcomic_list_collections($webcomic_arguments);
			endif;
			
			comments_template('', true);
		endwhile;
	?>
	
</main>

<?php get_sidebar(); get_footer();