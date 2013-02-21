<?php
/** Generic webcomic template.
 * 
 * Handles full webcomic image display with navigation. Used by the
 * `/webcomic/dynamic.php`, `/webcomic/single.php`, and
 * `/webcomic/index.php` templates, so changes here will affect how
 * full webcomics are displayed in all locations.
 * 
 * @package Inkblot
 */
?>

<nav class="webcomics above">
	<?php first_webcomic_link( '%link', get_theme_mod( 'first_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'first_webcomic_image' ), __( '&laquo;', 'inkblot' ) ) : '' ); ?>
	<?php previous_webcomic_link( '%link', get_theme_mod( 'previous_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'previous_webcomic_image' ), __( '&lsaquo;', 'inkblot' ) ) : '' ); ?>
	<?php random_webcomic_link( '%link', get_theme_mod( 'random_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'random_webcomic_image' ), __( '&infin;', 'inkblot' ) ) : '' ); ?>
	<?php next_webcomic_link( '%link', get_theme_mod( 'next_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'next_webcomic_image' ), __( '&rsaquo;', 'inkblot' ) ) : '' ); ?>
	<?php last_webcomic_link( '%link', get_theme_mod( 'last_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'last_webcomic_image' ), __( '&raquo;', 'inkblot' ) ) : '' ); ?>
</nav><!-- .webcomics.above -->
<div class="webcomic-image">
	<?php the_webcomic( 'full', get_theme_mod( 'webcomic_nav_link' ) ); ?>
</div><!-- .webcomic-image -->
<nav class="webcomics below">
	<?php first_webcomic_link( '%link', get_theme_mod( 'first_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'first_webcomic_image' ), __( '&laquo;', 'inkblot' ) ) : '' ); ?>
	<?php previous_webcomic_link( '%link', get_theme_mod( 'previous_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'previous_webcomic_image' ), __( '&lsaquo;', 'inkblot' ) ) : '' ); ?>
	<?php random_webcomic_link( '%link', get_theme_mod( 'random_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'random_webcomic_image' ), __( '&infin;', 'inkblot' ) ) : '' ); ?>
	<?php next_webcomic_link( '%link', get_theme_mod( 'next_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'next_webcomic_image' ), __( '&rsaquo;', 'inkblot' ) ) : '' ); ?>
	<?php last_webcomic_link( '%link', get_theme_mod( 'last_webcomic_image' ) ? sprintf( '<img src="%s" alt="%s">', get_theme_mod( 'last_webcomic_image' ), __( '&raquo;', 'inkblot' ) ) : '' ); ?>
</nav><!-- .webcomics.below -->