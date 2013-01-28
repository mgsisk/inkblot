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
	 * @uses InkblotMedia::display_media_states()
	 */
	public function __construct() {
		add_filter( 'display_media_states', array( $this, 'display_media_states' ), 10, 1 );
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
				$states[] = __( 'Webcomic Navigation Image', 'inkblot' );
			}
		}
		
		return $states;
	}
}