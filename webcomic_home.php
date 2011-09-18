<?php
/**
 * Template Name: Webcomic Home
 */

the_post(); get_header(); global $inkblot; $post_meta = current( get_post_meta( $post->ID, 'inkblot' ) );
?>

<div id="main">
	
	<?php if ( class_exists( 'webcomic' ) ) { ?>
	<section id="webcomic">
		<?php $i = $post_meta[ 'webcomic_collection' ]; if ( !empty( $i ) ) { $wc = get_term( ( int ) $i, 'webcomic_collection' ); $wcl = '&webcomic_collection=' . $wc->slug; } else $wcl = ''; $q = new WP_Query( 'post_type=webcomic_post&posts_per_page=1&order=' . $post_meta[ 'webcomic_order' ] . $wcl ); while ( $q->have_posts() ) { $q->the_post(); get_template_part( 'webcomic', 'home' ); } ?>
	</section>
	<?php } if ( '3c3' == $inkblot->option( 'layout' ) ) get_sidebar(); ?>
	
	<section id="content">
		<div>
			<div id="content-above" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-above' ); ?></div>
			
			<?php if ( class_exists( 'webcomic' ) ) { while ( $q->have_posts() ) { $q->the_post(); ?>
			
			<article id="webcomic-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<footer><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> | <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_date(); ?> @ <?php the_time(); ?></a> | <?php purchase_webcomic_link( '%link | ' ); comments_popup_link(); edit_post_link( NULL, ' | ' ); ?></footer>
				<div class="content"><?php the_content(); ?></div>
			</article><!-- #webcomic-<?php the_ID(); ?> -->
			<hr class="webcomic">
			
			<?php } } ?>
	</section><!--#content-->
</div><!--#main-->

<?php get_sidebar(); get_footer(); ?>