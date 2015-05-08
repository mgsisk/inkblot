<?php
/**
 * Feed integration template.
 * 
 * **NEVER** `print` or `echo` anything in this template. Content you want to
 * include with your Webcomic posts in site feeds must be assigned to the
 * `$append` or `$prepend` variables.
 * 
 * @package Inkblot
 */
$link = get_permalink(get_the_ID());
$images = '';

foreach ($attachments as $attachment) {
    $images .= wp_get_attachment_image($attachment->ID, $feed_size);
}

$prepend = "<p><a href='{$link}'>{$images}</a></p>";