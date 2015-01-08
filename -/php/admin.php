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
add_action('admin_head-appearance_page_custom-header', 'inkblot_admin_head_appearance_page_custom_header');

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
	
	if ( ! get_theme_mods()) {
		inkblot_notify(sprintf(__('Thank you for using %1$s!', 'inkblot'), '<a href="//github.com/mgsisk/inkblot" target="_blank">Inkblot</a>'), 'updated');
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
					if ($fonts = inkblot_google_fonts()) {
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

if ( ! function_exists('inkblot_admin_head_appearance_page_custom_header')) :
/**
 * Save Inkblot-specific header modifications.
 * 
 * @return void
 */
function inkblot_admin_head_appearance_page_custom_header() {
	if ( ! current_user_can('edit_theme_options') or empty($_POST)) {
		return;
	}
	
	check_admin_referer('custom-header-options', '_wpnonce-custom-header-options');
	
	set_theme_mod('header_textopacity', $_POST['header_textopacity']);
	set_theme_mod('header_font', $_POST['header_font']);
	set_theme_mod('header_width', $_POST['header_width']);
	set_theme_mod('header_height', $_POST['header_height']);
	
	return;
}
endif;

if ( ! function_exists('inkblot_admin_head')) :
/**
 * Render custom header preview CSS.
 * 
 * @return void
 */
function inkblot_admin_head() { ?>
	<style>
		#headimg {
			background-color: <?php print get_theme_mod('page_color', '#fff'); ?>;
			background-image: <?php print get_theme_mod('page_background_image', 'none'); ?>;
			background-repeat: <?php print get_theme_mod('page_background_repeat', 'repeat'); ?>;
			background-position: <?php print get_theme_mod('page_background_position', 'top left'); ?>;
			background-attachment: <?php print get_theme_mod('page_background_attachment', 'fixed'); ?>;
			cursor: pointer;
			font-family: <?php print get_theme_mod('font') ? str_replace('+', ' ', substr(get_theme_mod('font'), 0, strpos(get_theme_mod('font'), ':'))) : 'helvetica neue, helvetica'; ?>, sans-serif;
			line-height: 1.4;
			padding: 1px 1px 0;
			text-decoration: none;
			transition: opacity .2s;
			white-space: normal;
		}
		
		#headimg .displaying-header-text {
			font-family: <?php print get_theme_mod('page_font') ? str_replace('+', ' ', substr(get_theme_mod('page_font'), 0, strpos(get_theme_mod('page_font'), ':'))) : 'helvetica neue, helvetica'; ?>, sans-serif;
		}
		
		#headimg:focus,
		#headimg:hover {
			opacity: 0.8;
		}
		
		#headimg:active {
			opacity: 0.4;
		}
		
		#name {
			cursor: pointer;
			font-family: <?php print get_theme_mod('header_font') ? str_replace('+', ' ', substr(get_theme_mod('header_font'), 0, strpos(get_theme_mod('header_font'), ':'))) : 'helvetica neue, helvetica'; ?>, sans-serif;
			line-height: 1;
			margin: 0;
			padding: 1rem 1rem 0 1rem;
		}
		
		#desc {
			cursor: pointer;
			font-family: <?php print get_theme_mod('header_font') ? str_replace('+', ' ', substr(get_theme_mod('header_font'), 0, strpos(get_theme_mod('header_font'), ':'))) : 'helvetica neue, helvetica'; ?>, sans-serif;
			line-height: 2;
			margin: 0;
			padding: 0 1rem 1rem 1rem;
		}
		
		#headimg img {
			cursor: pointer;
			height: auto;
			max-width: 100%;
			vertical-align: middle;
		}
		
		<?php
			if ('blank' === get_header_textcolor()) {
				print '.displaying-header-text {display: none;}';
			} else if (get_header_textcolor()) {
				inkblot_css(array(
					'#name',
					'#desc',
				), 'color', array(get_theme_mod('header_textcolor'), get_theme_mod('header_textopacity')));
			} else {
				inkblot_css(array(
					'#name',
					'#desc'
				), 'color', '#222');
			}
			
			inkblot_css();
		?>
	</style>
	<?php
}
endif;

if ( ! function_exists('inkblot_admin_head_preview')) :
/**
 * Render custom header preview HTML.
 * 
 * @return void
 */
function inkblot_admin_head_preview() { ?>
	<div id="headimg">
		<div class="displaying-header-text">
			<h1 id="name"><?php bloginfo('name'); ?></h1>
			<p id="desc"><?php bloginfo('description'); ?></p>
		</div>
		
		<?php if (get_header_image()) : ?>
			<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
	<?php
}
endif;

if ( ! function_exists('inkblot_google_fonts')) :
/**
 * Return Google Font data.
 * 
 * @return object
 */
function inkblot_google_fonts() {
	$fonts = array();
	
	if ($fonts = wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyDGeJxu3MGJVi5RiUw4rQ3Jt_Q4VtSOnyE') and !is_wp_error($fonts)) {
		$fonts = json_decode($fonts['body']);
	}
	
	return $fonts;
}
endif;

if ( ! function_exists('inkblot_notify')) :
function inkblot_notify($message, $type = 'notice') {
	$notify = get_transient("inkblot_{$type}");
	
	set_transient("inkblot_{$type}", array_merge(array($message), $notify ? $notify : array()), 1);
}
endif;