<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11"><?php wp_head(); ?></head>
<body <?php body_class(); ?>>
	<div id="wrap-outer" class="group <?php inkblot_site_alignment(); ?>">
		<div id="wrap-inner">
			<div id="page">
				<?php get_sidebar( 'page-top' ); ?>
				<div id="head">
					<div class="group">
						<div class="interior">
							<div class="name"><a href="<?php bloginfo( 'home' ); ?>" title="<?php bloginfo( 'description' ); ?>"><span><?php bloginfo( 'name' ); ?></span></a></div>
							<div class="description"><?php bloginfo( 'description' ); ?></div>
						</div>
						<ul <?php inkblot_site_navi_class(); ?>>
							<li><a href="<?php bloginfo( 'home' ); ?>" title="<?php _e( 'Home', 'inkblot' ); ?>"><?php _e( 'Home', 'inkblot' ); ?></a></li>
							<?php wp_list_pages( 'title_li=&link_before=<span>&link_after=</span>' ); ?>
							<li class="alignright"><a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php _e( 'Subscribe', 'inkblot' ); ?>"><?php _e( 'Subscribe', 'inkblot' ); ?></a></li>
						</ul>
					</div>
				</div>
				<div id="body">
					<div class="group">
					<?php inkblot_begin_content(); ?>