<?php
/** Contains the InkblotPages class.
 * 
 * @package Inkblot
 */

/** Handle page-related tasks.
 * 
 * @package Inkblot
 */
class InkblotPages extends Inkblot {
	/** Register hooks.
	 * 
	 * @uses InkblotPages::add_meta_boxes()
	 * @uses InkblotPages::save_page()
	 * @uses InkblotPages::admin_enqueue_scripts()
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'wp_insert_post', array( $this, 'save_page' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}
	
	/** Add page meta boxes.
	 * 
	 * @hook add_meta_boxes
	 */
	public function add_meta_boxes() {
		add_meta_box( 'inkblot-options', __( 'Inkblot Options', 'webcomic' ), array( $this, 'inkblot_options' ), 'page', 'normal', 'high' );
	}
	
	/** Register and enqueue meta box scripts.
	 * 
	 * @uses Inkblot::$url
	 * @hook admin_enqueue_scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		
		if ( 'page' === $screen->id ) {
			wp_enqueue_script( 'inkblot-admin-pages', self::$url . '-/js/admin-pages.js', array( 'jquery' ) );
		}
	}
	
	/** Save metadata with pages.
	 * 
	 * @param integer $id The page ID to update.
	 * @param object $post Post object to update.
	 * @hook wp_insert_post
	 */
	public function save_page( $id, $post ) {
		if (
			isset( $_POST[ 'inkblot_meta_template' ] )
			and 'page' === $post->post_type
			and ( !defined( 'DOING_AUTOSAVE' ) or !DOING_AUTOSAVE )
			and wp_verify_nonce( $_POST[ 'inkblot_meta_template' ], 'inkblot_meta_template' )
			and current_user_can( 'edit_page', $id )
		) {
			if ( $post_id = wp_is_post_revision( $id ) ) {
				$id = $post_id;
			}
			
			if ( false !== strpos( $post->page_template, 'webcomic/' ) ) {
				update_post_meta( $id, 'inkblot_webcomic_group', $_POST[ 'inkblot_webcomic_group' ] );
				update_post_meta( $id, 'inkblot_webcomic_image', $_POST[ 'inkblot_webcomic_image' ] );
				update_post_meta( $id, 'inkblot_webcomic_order', $_POST[ 'inkblot_webcomic_order' ] );
			} else {
				delete_post_meta( $id, 'inkblot_webcomic_group' );
				delete_post_meta( $id, 'inkblot_webcomic_image' );
				delete_post_meta( $id, 'inkblot_webcomic_order' );
			}
		}
	}
	
	/** Render the webcomic template meta box.
	 * 
	 * @param object $page Current page object.
	 * @uses webcomic()
	 * @uses get_webcomic_collections()
	 */
	public function inkblot_options( $page ) {
		if ( webcomic() ) {
			wp_nonce_field( 'inkblot_meta_template', 'inkblot_meta_template' );
			
			$collections    = get_webcomic_collections( true );
			$webcomic_group = get_post_meta( $page->ID, 'inkblot_webcomic_group', true );
			$webcomic_image = get_post_meta( $page->ID, 'inkblot_webcomic_image', true );
			$webcomic_order = get_post_meta( $page->ID, 'inkblot_webcomic_order', true );
			?>
			<div data-inkblot-options="none">
				<p><strong><?php _e( 'Select one of the following templates to modify template-specific options:', 'inkblot' ); ?></strong></p>
				<ul>
					<?php
						foreach ( get_page_templates() as $k => $v ) {
							if ( false !== strpos( $v, 'webcomic/' ) ) {
								printf( '<li>%s</li>', $k );
							}
						}
					?>
				</ul>
			</div>
			<div data-inkblot-options="webcomic/template-archive.php">
				<h4><?php _e( 'Webcomic Archive Options', 'inkblot' ); ?></h4>
				<?php
					$select = '';
					
					foreach ( get_intermediate_image_sizes() as $size ) {
						$select .= sprintf( '<option value="%s"%s>%s</option>',
							$size,
							selected( $size, $webcomic_image, false ),
							$size
						);
					}
					
					printf( __( '<label>Show webcomics as %1$s grouped by %2$s</label>', 'inkblot' ),
						sprintf( '
							<select name="inkblot_webcomic_image">
								<option value="">%s</option>
								%s
							</select>',
							__( 'text links', 'inkblot' ),
							$select
						),
						sprintf( '
							<select name="inkblot_webcomic_group">
								<option value="">%s</option>
								<option value="storyline"%s>%s</option>
								<option value="character"%s>%s</option>
							</select>',
							__( 'collection', 'inkblot' ),
							selected( 'storyline', $webcomic_group, false ),
							__( 'storyline', 'inkblot' ),
							selected( 'character', $webcomic_group, false ),
							__( 'character', 'inkblot' )
						)
					);
				?>
			</div>
			<div data-inkblot-options="webcomic/template-home.php">
				<h4><?php _e( 'Webcomic Homepage Options', 'inkblot' ); ?></h4>
				<?php
					printf( __( '<label>Show the %1$s webcomic</label>', 'inkblot' ),
						sprintf( '
							<select name="inkblot_webcomic_order">
								<option value="ASC"%s>%s</option>
								<option value="DESC"%s>%s</option>
							</select>',
							selected( 'ASC', $webcomic_order, false ),
							__( 'chronologically first', 'inkblot' ),
							selected( 'DESC', $webcomic_order, false ),
							__( 'most recently published', 'inkblot' )
						)
					);
				?>
			</div>
			<?php
		} else {
			?>
			<p><strong><?php printf( __( '<a href="%s" target="_blank">Webcomic</a> is not installed. Selecting the following templates will not change the appearance of this page:', 'inkblot' ), 'http://webcomic.nu' ); ?></strong></p>
			<ul>
				<?php
					foreach ( get_page_templates() as $k => $v ) {
						if ( false !== strpos( $v, 'webcomic/' ) ) {
							printf( '<li>%s</li>', $k );
						}
					}
				?>
			</ul>
			<?php
		}
	}
}