<?php
/** Single post template.
 * 
 * This template is nearly identical to the normal `single.php`
 * template, except for the addition of webcomic display using the
 * separate `/webcomic/webcomic.php` template.
 * 
 * @package Inkblot
 */
?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php if ( !get_theme_mod( 'webcomic_content', false ) ) : ?>
		<div id="webcomic" class="post-webcomic" data-webcomic-shortcuts>
			<?php get_template_part( 'webcomic/webcomic', get_post_type() ); ?>
		</div><!-- .post-webcomic -->
	<?php endif; ?>
	<main role="main">
		<?php if ( get_theme_mod( 'webcomic_content', false ) ) : ?>
			<div id="webcomic" class="post-webcomic" data-webcomic-shortcuts>
				<?php get_template_part( 'webcomic/webcomic', get_post_type() ); ?>
			</div><!-- .post-webcomic -->
		<?php endif; ?>
		<?php get_template_part( 'webcomic/content', get_post_type() ); ?>
		<?php webcomic_transcripts_template(); ?>
		<?php comments_template( '', true ); ?>
	</main>
<?php endwhile; ?>