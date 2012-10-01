<?php
/** Basic feed integration.
 * 
 * This template is used when adding webcomic previews to site
 * feeds. Content must be assigned to the `$prepend` and `$append`
 * variables. The `$attachments` variable is an array of
 * Webcomic-recognized WordPress attachments, and the `$feed_size`
 * variable stores the preview size selected in the current
 * collections settings.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */
$prepend = sprintf( '<p><a href="%s">', get_permalink() );

foreach ( $attachments as $attachment ) {
	$prepend .= wp_get_attachment_image( $attachment->ID, $feed_size );
}

$prepend .= '</a></p>';