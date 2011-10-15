<?php
/**
 * This file contains everything for the following templates:
 * 
 * home.php
 * single-webcomic_post.php
 * single.php
 * page.php
 * search.php
 * archive.php
 * 404.php
 * 
 * You can change any of these by modifying this file directly or
 * creating the missing template file. For more information, see
 * http://codex.wordpress.org/Template_Hierarchy
 */
 global $webcomic, $inkblot;
?>

<?php get_header(); ?>

<div id="main">
	
	<?php if ( class_exists( 'webcomic' ) ) { ?>
	<section id="webcomic">
		<?php if ( is_home() && !is_paged() && $inkblot->option( 'home_webcomic_toggle' ) ) { $i = $inkblot->option( 'home_webcomic_collection' ); if ( !empty( $i ) ) { $wc = get_term( ( int ) $i, 'webcomic_collection' ); $wcl = '&webcomic_collection=' . $wc->slug; } else $wcl = ''; $q = new WP_Query( 'post_type=webcomic_post&posts_per_page=1&order=' . $inkblot->option( 'home_webcomic_order' ) . $wcl ); while ( $q->have_posts() ) { $q->the_post(); get_template_part( 'webcomic', 'home' ); } } elseif ( is_singular( 'webcomic_post' ) ) { get_template_part( 'webcomic', 'single' ); } ?>
	</section>
	<?php } if ( '3c3' == $inkblot->option( 'layout' ) ) get_sidebar(); ?>
	
	<section id="content">
		<div>
			<div id="content-above" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-above' ); ?></div>
		
		<?php if ( is_home() ) { /* home.php */ ?>
			
			<?php if ( class_exists( 'webcomic' ) && $inkblot->option( 'home_webcomic_toggle' ) && !is_paged() ) { while ( $q->have_posts() ) { $q->the_post(); ?>
			
			<article id="webcomic-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<footer><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> | <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_date(); ?> @ <?php the_time(); ?></a> | <?php purchase_webcomic_link( '%link | ' ); comments_popup_link(); edit_post_link( NULL, ' | ' ); ?></footer>
				<div class="content"><?php the_content(); ?></div>
			</article><!-- #webcomic-<?php the_ID(); ?> -->
			<hr class="webcomic">
			
			<?php } } get_template_part( 'loop', 'home' ); ?>
			
		<?php } elseif ( is_singular( 'webcomic_post' ) ) { the_post(); /* single-webcomic_post.php */ ?>
		
			<article id="webcomic-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<div class="content"><?php the_content(); ?></div>
				<?php if ( $inkblot->option( 'embed_webcomic_toggle' ) ) { ?><div class="embed"><?php the_webcomic_embed( $inkblot->option( 'embed_webcomic_format' ), $inkblot->option( 'embed_webcomic_size' ) ); ?></div><?php } ?>
				<footer>
				<?php
					purchase_webcomic_link( '%link | ' );
					
					printf( __( 'Posted on <a href="%s" rel="bookmark">%s</a> by <a href="%s" rel="author">%s</a>', 'inkblot' ), get_permalink(), get_the_date(), get_author_posts_url( get_the_author_meta( 'ID' ) ), get_the_author() );
					
					the_webcomic_post_collections( array( 'before' => __( ' | From ', 'inkblot' ), 'separator' => ', ' ) );
					the_webcomic_post_storylines( array( 'before' => __( ' | Part of ', 'inkblot' ), 'separator' => ', ' ) );
					the_webcomic_post_characters( array( 'before' => __( ' | Featuring ', 'inkblot' ), 'separator' => ', ' ) );
					
					if ( get_the_category() ) { _e( ' | Filed under ', 'inkblot' ); the_category( ', ' ); the_tags( __( ' and tagged with ', 'inkblot' ), ', ' ); } else the_tags( __( ' | Tagged with ' ), ', ' );
					
					edit_post_link( __( 'Edit', 'inkblot' ), ' | ' );
				?>
				</footer>
				<?php webcomic_transcripts_template(); comments_template(); ?>
			</article><!-- #post-<?php the_ID(); ?> -->
			
		<?php } elseif ( is_single() ) { the_post(); /* single.php */ ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<nav class="prevnext">
				<?php if ( is_attachment() ) { ?>
					<a href="<?php echo get_permalink( $post->post_parent ); ?>" rel="gallery">&laquo; <?php echo get_the_title( $post->post_parent ); ?></a>
				<?php } else { previous_post_link( '%link', '&laquo; %title' ); next_post_link( '%link', '%title &raquo;' ); } ?>
				</nav>
			
				<h1><?php the_title(); ?></h1>
				<div class="content"><?php the_content(); wp_link_pages( array( 'before' => '<nav class="paginated">', 'after' => '</nav>' ) ); ?></div>
				<?php if ( is_attachment() ) { ?><nav class="prevnext"><?php previous_image_link(); next_image_link(); ?></nav><?php } ?>
				<footer>
				<?php
					printf( __( 'Posted on <a href="%s" rel="bookmark">%s</a> by <a href="%s" rel="author">%s</a>', 'inkblot' ), get_permalink(), get_the_date(), get_author_posts_url( get_the_author_meta( 'ID' ) ), get_the_author() );
					
					if ( get_the_category() ) { _e( ' | Filed under ', 'inkblot' ); the_category( ', ' ); the_tags( __( ' and tagged with ', 'inkblot' ), ', ' ); } else the_tags( __( ' | Tagged with ' ), ', ' );
					
					edit_post_link( __( 'Edit', 'inkblot' ), ' | ' );
				?>
				</footer>
				<?php comments_template( '', true ); ?>
			</article><!-- #post-<?php the_ID(); ?> -->
			
		<?php } elseif ( is_page() ) { the_post(); /* page.php */ ?>
		
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<div class="content"><?php the_content(); wp_link_pages( array( 'before' => '<nav class="paginated">', 'after' => '</nav>' ) ); edit_post_link( __( 'Edit', 'inkblot' ), '<p>', '</p>' ); ?></div>
				<?php comments_template( '', true ); ?>
			</article><!-- #post-<?php the_ID(); ?> -->
			
		<?php } elseif ( is_search() ) { /* search.php */ ?>
			
			<?php if ( have_posts() ) { ?>
			
			<h1><?php printf( __( 'Search Results for %s', 'inkblot' ), '<q>' . esc_html( get_search_query() ) . '</q>' ); ?></h1>
			
			<?php get_template_part( 'loop', 'search' ); } else { ?>
			
			<article id="post-0" class="post no-results not-found">
				<h1><?php _e( 'Nothing Found', 'inkblot' ); ?></h1>
				<p><?php _e( 'Nothing matched your search, please try again: ', 'inkblot' ); get_search_form(); ?></p>
			</article>
			
			<?php } ?>
			
		<?php } elseif ( is_archive() ) { the_post(); /* archive.php */ ?>
			
			<?php if ( is_date() ) { ?><h1><?php if ( is_year() ) $d = get_the_date( 'Y' ); elseif ( is_month() ) $d = get_the_time( 'F Y' ); else $d = get_the_time( get_option( 'date_format' ) ); printf( __( 'Posts from %s', 'inkblot' ), $d ); ?></h1>
			<?php } elseif ( is_category() ) { ?><h1><?php printf( __( 'Posts filed under %s', 'inkblot' ), single_cat_title( '', false ) ); ?></h1>
			<?php } elseif ( is_tag() ) { ?><h1><?php printf( __( 'Posts tagged with %s', 'inkblot' ), single_tag_title( '', false ) ); ?></h1>
			<?php } elseif ( is_author() ) { if ( !is_paged() ) { ?>
					<h1><?php the_author(); ?></h1>
					<div class="author-avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 128 ); ?></div>
					<div class="content"><?php the_author_meta( 'description' ); ?></div>
					<hr>
					<h2><?php printf( __( 'Posts by %s', 'inkblot' ), get_the_author() ); ?></h2>
				<?php } else { ?>
					<h1><?php printf( __( 'Posts by %s', 'inkblot' ), get_the_author() ); ?></h1>
				<?php } ?>
			<?php } elseif ( is_tax( 'webcomic_collection' ) ) { ?><h1><?php printf( __( 'Webcomics from %s', 'inkblot' ), $webcomic->get_webcomic_term_info( 'name', 'webcomic_collection' ) ); ?></h1>
			<?php } elseif ( is_tax( 'webcomic_storyline' ) ) { ?><h2><?php printf( __( 'Webcomics in %s', 'inkblot' ), $webcomic->get_webcomic_term_info( 'name', 'webcomic_storyline' ) ); ?></h2>
			<?php } elseif ( is_tax( 'webcomic_character' ) ) { query_posts( $query_string . '&order=ASC' ); if ( !is_paged() ) { ?>
				<h1><?php webcomic_character_info( 'name' ); ?></h1>
				<div class="character-avatar"><?php webcomic_character_info( 'thumb-full' ); ?></div>
				<div class="content"><?php webcomic_character_info( 'description' ); ?></div>
				<nav class="prevnext"><?php previous_webcomic_character_link( '%link', '&laquo; %name' ); next_webcomic_character_link( '%link', '%name &raquo;' ); ?></nav>
				<hr>
				<h2><?php printf( __( 'Appearances by %s', 'inkblot' ), $webcomic->get_webcomic_term_info( 'name', 'webcomic_character' ) ); ?></h2>
				<?php } else { ?>
				<h1><?php printf( __( 'Appearances by %s', 'inkblot' ), $webcomic->get_webcomic_term_info( 'name', 'webcomic_character' ) ); ?></h1>
				<?php } ?>
			<?php } else { ?><h1><?php _e( 'Archives', 'inkblot' ); ?></h1>
			<?php } rewind_posts(); get_template_part( 'loop', 'archive' ); ?>
			
		<?php } elseif ( is_404() ) { /* 404.php */ ?>
			
			<article id="post-0" class="post error404 not-found">
				<h1><?php _e( '404 Not Found', 'inkblot' ); ?></h1>
				<p><?php get_search_form(); ?></p>
			</article>
			
		<?php } ?>
		
			<div id="content-below" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-below' ); ?></div>
		</div>
	</section><!--#content-->
</div><!--#main-->

<?php get_sidebar(); get_footer(); ?>