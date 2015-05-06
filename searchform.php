<?php
/**
 * Search form template.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Function_Reference/get_search_form
 */
?>

<form action="<?php print esc_url(home_url('/')); ?>" role="search" class="search">
	<p>
		<label for="<?php print inkblot_search_id(); ?>"><?php _e('Search', 'inkblot'); ?></label>
		<input type="search" id="<?php print inkblot_search_id(false); ?>" name="s">
	</p>
	<p>
		<button type="submit"><?php _e('Search', 'inkblot'); ?></button>
	</p>
</form><!-- .search -->