<?php
/**
 * Template Name: Webcomic Archive
 * 
 * Useful for creating a more traditional webcomic archive page.
 * 
 * @package Inkblot
 */

if ( !webcomic() ) {
	get_template_part( 'page' );
	return;
}

get_header();

$webcomic_group      = get_post_meta( get_the_ID(), 'inkblot_webcomic_group', true );
$webcomic_image      = get_post_meta( get_the_ID(), 'inkblot_webcomic_image', true );
$webcomic_collection = get_post_meta( get_the_ID(), 'webcomic_collection', true );
?>

<main role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'page' ); ?>
	<?php endwhile;?>
	<?php if ( 'character' === $webcomic_group ) : ?>
		<?php webcomic_list_characters( array( 'webcomics' => true, 'target' => 'first', 'webcomic_image' => $webcomic_image, 'collection' => $webcomic_collection ) ); ?>
	<?php elseif ( 'storyline' === $webcomic_group ) : ?>
		<?php webcomic_list_storylines( array( 'webcomics' => true, 'target' => 'first', 'webcomic_image' => $webcomic_image, 'collection' => $webcomic_collection ) ); ?>
	<?php else : ?>
		<?php webcomic_list_collections( array( 'webcomics' => true, 'target' => 'first', 'webcomic_image' => $webcomic_image, 'collection' => $webcomic_collection ) ); ?>
	<?php endif; ?>
	<?php comments_template( '', true ); ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>