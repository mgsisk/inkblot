<?php
/**
 * Webcomic home template.
 * 
 * This is the Webcomic homepage template (used in place of the normal
 * `home.php` or `index.php` when Webcomic is active). The only real difference
 * between this template and the normal template is the inclusion of a webcomic.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_header(); ?>

<?php
	$webcomics = false;
	
	if (get_theme_mod('webcomic_home_order', 'DESC') and ! is_paged()) :
		$webcomics = new WP_Query(array(
			'order' => get_theme_mod('webcomic_home_order', 'DESC'),
			'post_type' => get_theme_mod('webcomic_home_collection', '') ? get_theme_mod('webcomic_home_collection', '') : get_webcomic_collections(),
			'posts_per_page' => 1
		));
	endif;
?>

<?php
	if ($webcomics and ! get_theme_mod('webcomic_content', false) and $webcomics->have_posts()) :
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
			
			if (get_theme_mod('webcomic_front_page_transcripts', false)) :
				webcomic_transcripts_template();
			endif;
			
			if (get_theme_mod('webcomic_front_page_comments', false)) :
				$withcomments = true;
				
				comments_template();
				
				$withcomments = false;
			endif;
		endif;
		
		// Blog posts
		if (have_posts()) :
			while (have_posts()) : the_post();
				get_template_part('content', get_post_format());
			endwhile;
			
			print inkblot_posts_nav(false, get_theme_mod('paged_navigation', true));
		else :
			get_template_part('content', 'none');
		endif;
	?>
	
</main>

<?php get_sidebar(); get_footer();