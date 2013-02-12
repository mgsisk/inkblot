<?php
/** Search form template.
 * 
 * @package Inkblot
 */
?>

<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search">
	<label for="<?php inkblot_search_id(); ?>" class="ghost focus"><?php _e( 'Search', 'inkblot' ); ?></label>
	<input type="search" id="<?php inkblot_search_id( false ); ?>" name="s" placeholder="<?php esc_attr_e( 'Search', 'inkblot' ); ?>">
	<button type="submit"><?php _e( 'Search', 'inkblot' ); ?></button>
</form><!-- .search -->