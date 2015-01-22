<?php
/**
 * Contains the Inkblot administrative functions.
 * 
 * @package Inkblot
 */

add_action('admin_notices', 'inkblot_admin_notices');
add_action('after_switch_theme', 'inkblot_after_switch_theme');
add_action('custom_header_options', 'inkblot_custom_header_options');
add_action('admin_enqueue_scripts', 'inkblot_admin_enqueue_scripts', 10, 1);

if ( ! function_exists('inkblot_admin_notices')) :
function inkblot_admin_notices() {
	foreach (array('updated', 'error') as $type) {
		if ($notice = get_transient("inkblot_{$type}")) {
			delete_transient("inkblot_{$type}");
			
			print "<div class='{$type}'><p>" . implode("</p></div><div class='{$type}'></p>", $notice) . "</p></div>";
		}
	}
}
endif;

if ( ! function_exists('inkblot_after_switch_theme')) :
/**
 * Activation hook.
 * 
 * @return void
 * @hook after_switch_theme
 */
function inkblot_after_switch_theme() {
	if (get_theme_mod('uninstall')) {
		remove_theme_mods();
	}
}
endif;

if ( ! function_exists('inkblot_custom_header_options')) :
/**
 * Render Inkblot-specific header options.
 * 
 * @return void
 * @hook custom_header_options
 */
function inkblot_custom_header_options() { ?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="inkblot_header_textopacity"><?php _e('Text Opacity', 'inkblot'); ?></label>
			</th>
			<td>
				<input type="range" id="inkblot_header_textopacity" name="header_textopacity" value="<?php print get_theme_mod('header_textopacity', 1); ?>" min="0" max="1" step=".05">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="inkblot_header_font"><?php _e('Font', 'inkblot'); ?></label>
			</th>
			<td>
				<?php
					if ($fonts = inkblot_get_fonts()) {
						$header_font = get_theme_mod('header_font');
						
						print '<select id="inkblot_header_font" name="header_font">';
						print '<option value="">(inherit)</option>';
						
						foreach ($fonts->items as $font) {
							$value = sprintf('%s:%s', str_replace(' ', '+', $font->family), implode(',', $font->variants));
							
							printf("<option value='%s'%s>%s</option>",
								$value,
								selected($value, $header_font),
								$font->family
							);
						}
						
						print '</select>';
					} else {
						printf(__("Sorry, we couldn't connect to %s", 'inkblot'), '<a href="http://google.com/fonts" target="_blank">Google Fonts</a>');
					}
				?>
			</td>
		</tr>
	</table>
	<h3><?php _e('Header Dimensions', 'inkblot'); ?></h3>
	<p><?php _e('Save changes to the width and height before uploading your header image. Changes will not affect previously uploaded header images.', 'inkblot'); ?></p>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="inkblot_header_width"><?php _e('Width', 'inkblot'); ?></label>
			</th>
			<td>
				<input type="number" id="inkblot_header_width" name="header_width" value="<?php print get_theme_mod('header_width', 960); ?>" min="0" step="1">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="inkblot_header_height"><?php _e('Height', 'inkblot'); ?></label>
			</th>
			<td>
				<input type="number" id="inkblot_header_height" name="header_height" value="<?php print get_theme_mod('header_height', 240); ?>" min="0" step="1">
			</td>
		</tr>
	</table>
	<?php
}
endif;

if ( ! function_exists('inkblot_admin_enqueue_scripts')) :
/**
 * Register and enqueue page-specific scirpts.
 * 
 * @return void
 * @hook admin_enqueue_scripts
 */
function inkblot_admin_enqueue_scripts($page) {
	global $post;
	
	if ('post.php' === $page and $post and 'page' === $post->post_type) {
		wp_enqueue_script('inkblot-templates-script', get_template_directory_uri() . '/-/js/templates.js', array('jquery'));
	} else if ('appearance_page_custom-header' === $page) {
		if (get_theme_mod('font') or get_theme_mod('page_font')) {
			$proto = is_ssl() ? 'https' : 'http';
			$fonts = array_filter(array(
				get_theme_mod('font'),
				get_theme_mod('page_font')
			));
			
			wp_enqueue_style('inkblot-fonts', add_query_arg(array('family' => implode('|', $fonts)), "{$proto}://fonts.googleapis.com/css"));
		}
		
		wp_enqueue_script('inkblot-custom-header', get_template_directory_uri() . '/-/js/admin-custom-header.js', array('jquery'), '', true);
	}
}
endif;

if ( ! function_exists('inkblot_get_fonts')) :
/**
 * Return Google Font data.
 * 
 * @return object
 */
function inkblot_get_fonts() {
	$fonts = array();
	
	if ($fonts = wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyDGeJxu3MGJVi5RiUw4rQ3Jt_Q4VtSOnyE') and !is_wp_error($fonts)) {
		$fonts = json_decode($fonts['body']);
	}
	
	return is_wp_error($fonts) ? false : $fonts;
}
endif;

if ( ! function_exists('inkblot_notify')) :
function inkblot_notify($message, $type = 'notice') {
	$notify = get_transient("inkblot_{$type}");
	
	set_transient("inkblot_{$type}", array_merge(array($message), $notify ? $notify : array()), 1);
}
endif;