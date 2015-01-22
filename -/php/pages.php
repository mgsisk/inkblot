<?php
/**
 * Contains page-related functions specific to Inkblot.
 * 
 * @package Inkblot
 */

add_action('add_meta_boxes', 'inkblot_add_meta_boxes');
add_action('wp_insert_post', 'inkblot_insert_page', 10, 2);

if ( ! function_exists('inkblot_add_meta_boxes')) :
/**
 * Add page meta boxes.
 * 
 * @return void
 * @hook add_meta_boxes
 */
function inkblot_add_meta_boxes() {
	add_meta_box('inkblot-template-options', __('Template Options', 'webcomic'), 'inkblot_template_options', 'page', 'normal', 'high');
}
endif;

if ( ! function_exists('inkblot_insert_page')) :
/**
 * Save metadata with pages.
 * 
 * @param integer $id ID of the page to update.
 * @param object $post Post object to update.
 * @return void
 * @hook wp_insert_post
 */
function inkblot_insert_page($id, $post) {
	if (
		isset($_POST['inkblot_template_options'])
		and 'page' === $post->post_type
		and ( ! defined('DOING_AUTOSAVE') or ! DOING_AUTOSAVE)
		and wp_verify_nonce($_POST['inkblot_template_options'], 'inkblot_template_options')
		and current_user_can('edit_page', $id)
	) {
		if ($post_id = wp_is_post_revision($id)) {
			$id = $post_id;
		}
		
		$keys = array(
			'inkblot_avatar',
			'inkblot_sidebars',
			'inkblot_webcomic_group',
			'inkblot_webcomic_image',
			'inkblot_webcomic_order',
			'inkblot_webcomic_comments',
			'inkblot_webcomic_term_image',
			'inkblot_webcomic_transcripts'
		);
		
		if (in_array($post->page_template, array(
			'template/contributors.php',
			'template/full-width.php',
			'template/webcomic-archive.php',
			'template/webcomic-homepage.php',
			'template/webcomic-infinite.php'
		))) {
			foreach ($keys as $key) {
				update_post_meta($id, $key, $_POST[$key]);
			}
		} else {
			foreach ($keys as $key) {
				delete_post_meta($id, $key);
			}
		}
	}
}
endif;

if ( ! function_exists('inkblot_template_options')) :
/**
 * Render the webcomic template meta box.
 * 
 * @param object $page Current page object.
 * @uses webcomic()
 * @uses get_webcomic_collections()
 */
function inkblot_template_options($page) {
	wp_nonce_field('inkblot_template_options', 'inkblot_template_options');
	
	$inkblot_avatar = get_post_meta($page->ID, 'inkblot_avatar', true);
	$inkblot_sidebars = get_post_meta($page->ID, 'inkblot_sidebars', true);
	
	$webcomic_group = get_post_meta($page->ID, 'inkblot_webcomic_group', true);
	$webcomic_image = get_post_meta($page->ID, 'inkblot_webcomic_image', true);
	$webcomic_order = get_post_meta($page->ID, 'inkblot_webcomic_order', true);
	$webcomic_comments = get_post_meta($page->ID, 'inkblot_webcomic_comments', true);
	$webcomic_term_image = get_post_meta($page->ID, 'inkblot_webcomic_term_image', true);
	$webcomic_transcripts = get_post_meta($page->ID, 'inkblot_webcomic_transcripts', true); ?>
	<div data-inkblot-template-options="none">
		<p><strong><?php _e('Select one of the following templates to modify template-specific options:', 'inkblot'); ?></strong></p>
		<ul>
			<?php
				foreach (get_page_templates() as $k => $v) {
					if (in_array($v, array(
						'template/contributors.php',
						'template/full-width.php',
						'template/webcomic-archive.php',
						'template/webcomic-homepage.php',
						'template/webcomic-infinite.php'
					))) {
						printf('<li>%s</li>', $k);
					}
				}
			?>
		</ul>
	</div>
	<div data-inkblot-template-options="template/contributors.php">
		<h4><?php _e('Contributors', 'inkblot'); ?></h4>
		<p>
			<input id="inkblot_avatar" name="inkblot_avatar" type="number" min="0" step="8" class="small-text" value="<?php print (int) $inkblot_avatar; ?>">
			<label for="inkblot_avatar"><?php _e('Avatar size', 'inkblot'); ?></label>
		</p>
	</div>
	
	<div data-inkblot-template-options="template/full-width.php">
		<h4><?php _e('Full Width', 'inkblot'); ?></h4>
		<p>
			<input id="inkblot_sidebars" name="inkblot_sidebars" type="checkbox" value="1"<?php print $inkblot_sidebars ? ' checked' : ''; ?>>
			<label for="inkblot_sidebars"><?php _e('Show sidebars below the page content', 'inkblot'); ?></label>
		</p>
	</div>
	
	<div data-inkblot-template-options="template/webcomic-archive.php">
		<h4><?php _e('Webcomic Archive', 'inkblot'); ?></h4>
		<?php if (webcomic()) : ?>
			
			<p>
				
				<?php
					$select_img = $select_term_img = '';
					
					foreach (get_intermediate_image_sizes() as $size) {
						$select_img .= sprintf('<option value="%s"%s>%s</option>',
							$size,
							selected($size, $webcomic_image, false),
							$size
						);
						
						$select_term_img .= sprintf('<option value="%s"%s>%s</option>',
						 	$size,
						 	selected($size, $webcomic_term_image, false),
						 	$size
						 );
					}
					
					printf(__('<label>Show webcomic links as %1$s grouped by %2$s with %3$s term links</label>', 'inkblot'),
						sprintf('
							<select name="inkblot_webcomic_image">
								<option value="">%s</option>
								%s
							</select>',
							__('text', 'inkblot'),
							$select_img
						),
						sprintf('
							<select name="inkblot_webcomic_group">
								<option value="">%s</option>
								<option value="storyline"%s>%s</option>
								<option value="character"%s>%s</option>
							</select>',
							__('collection', 'inkblot'),
							selected('storyline', $webcomic_group, false),
							__('storyline', 'inkblot'),
							selected('character', $webcomic_group, false),
							__('character', 'inkblot')
						),
						sprintf('
							<select name="inkblot_webcomic_term_image">
								<option value="">%s</option>
								%s
							</select>',
							__('text', 'inkblot'),
							$select_term_img
						)
					);
				?>
				
			</p>
			
		<?php else : ?>
			
			<p><?php printf(__('It looks like %s is not installed or activated. This template will not affect the appearance of this page.', 'inkblot'), '<a href="http://wordpress.org/plugins/webcomic" target="_blank">Webcomic</a>'); ?></p>
			
		<?php endif; ?>
	</div>
	
	<div data-inkblot-template-options="template/webcomic-homepage.php">
		<h4><?php _e('Webcomic Homepage', 'inkblot'); ?></h4>
		
		<?php if (webcomic()) : ?>
			<p>
				
				<?php
					printf(__('<label>Show the %1$s webcomic</label>', 'inkblot'),
						sprintf('
							<select name="inkblot_webcomic_order">
								<option value="ASC"%s>%s</option>
								<option value="DESC"%s>%s</option>
							</select>',
							selected('ASC', $webcomic_order, false),
							__('first', 'inkblot'),
							selected('DESC', $webcomic_order, false),
							__('latest', 'inkblot')
						)
					);
				?>
				
			</p>
			<p>
				<input id="inkblot_webcomic_transcripts" name="inkblot_webcomic_transcripts" type="checkbox" value="1"<?php print $webcomic_transcripts ? ' checked' : ''; ?>>
				<label for="inkblot_webcomic_transcripts"><?php _e('Show transcripts', 'inkblot'); ?></label>
			</p>
			<p>
				<input id="inkblot_webcomic_comments" name="inkblot_webcomic_comments" type="checkbox" value="1"<?php print $webcomic_comments ? ' checked' : ''; ?>>
				<label for="inkblot_webcomic_comments"><?php _e('Show comments', 'inkblot'); ?></label>
			</p>
			
		<?php else : ?>
			
			<p><?php printf(__('It looks like %s is not installed or activated. This template will not affect the appearance of this page.', 'inkblot'), '<a href="http://wordpress.org/plugins/webcomic" target="_blank">Webcomic</a>'); ?></p>
			
		<?php endif; ?>
	</div>
	
	<div data-inkblot-template-options="template/webcomic-infinite.php">
		<h4><?php _e('Webcomic Infinite', 'inkblot'); ?></h4>
		
		<?php if (webcomic()) : ?>
			<p>
				
				<?php
					printf(__('<label>Start with the %1$s webcomic</label>', 'inkblot'),
						sprintf('
							<select name="inkblot_webcomic_order">
								<option value="ASC"%s>%s</option>
								<option value="DESC"%s>%s</option>
							</select>',
							selected('ASC', $webcomic_order, false),
							__('first', 'inkblot'),
							selected('DESC', $webcomic_order, false),
							__('latest', 'inkblot')
						)
					);
				?>
				
			</p>
			
		<?php else : ?>
			
			<p><?php printf(__('It looks like %s is not installed or activated. This template will not affect the appearance of this page.', 'inkblot'), '<a href="http://wordpress.org/plugins/webcomic" target="_blank">Webcomic</a>'); ?></p>
			<input type="hidden" name="inkblot_webcomic_group" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_group', true); ?>">
			<input type="hidden" name="inkblot_webcomic_image" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_image', true); ?>">
			<input type="hidden" name="inkblot_webcomic_order" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_order', true); ?>">
			<input type="hidden" name="inkblot_webcomic_comments" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_comments', true); ?>">
			<input type="hidden" name="inkblot_webcomic_term_image" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_term_image', true); ?>">
			
		<?php endif; ?>
	</div>
	<?php
}
endif;