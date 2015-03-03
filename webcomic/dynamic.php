<?php
/**
 * Automagic integration for dynamic requests.
 * 
 * This template is used to render webcomics using Webcomic's Dynamic
 * Navigation feature. We're just using the `/webcomic/content-webcomic.php`
 * template to keep things simple.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_template_part('webcomic/display', get_post_type());