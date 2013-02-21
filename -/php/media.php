<?php
/** Contains the InkblotMedia class.
 * 
 * @package Inkblot
 */

/** Handle media-related tasks.
 * 
 * @package Inkblot
 */
class InkblotMedia extends Inkblot {
	/** Register hooks.
	 * 
	 * @uses InkblotMedia::delete_attachment()
	 * @uses InkblotMedia::display_media_states()
	 */
	public function __construct() {
		add_action( 'delete_attachment', array( $this, 'delete_attachment' ), 10, 1 );
		
		add_filter( 'display_media_states', array( $this, 'display_media_states' ), 10, 1 );
	}
	
	/** Update image theme mods when attachments are deleted.
	 * 
	 * @hook delete_attachment
	 */
	public function delete_attachment( $id ) {
		foreach ( array(
			'page_background_image',
			'previous_webcomic_image',
			'next_webcomic_image',
			'first_webcomic_image',
			'last_webcomic_image',
			'random_webcomic_image'
		) as $mod ) {
			if ( get_theme_mod( $mod ) === wp_get_attachment_url( $id ) ) {
				set_theme_mod( $mod, '' );
			}
		}
	}
	
	/** Display relevant status for theme media.
	 * 
	 * @param array $states List of media states.
	 * @return array
	 * @hook display_media_states
	 */
	public function display_media_states( $states ) {
		global $post;
		
		if ( $type = get_post_meta( $post->ID, '_wp_attachment_context', true ) and preg_match( '/^inkblot-.+$/', $type ) ) {
			if ( 'inkblot-page-background' === $type ) {
				$states[] = __( 'Inkblot Page Background', 'inkblot' );
			} elseif ( 'inkblot-webcomic-navigation-image' === $type ) {
				$states[] = __( 'Inkblot Webcomic Navigation Image', 'inkblot' );
			}
		}
		
		return $states;
	}
}