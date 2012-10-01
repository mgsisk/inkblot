<?php
/** Footer template.
 * 
 * @package Inkblot
 */
?>
				<div id="content-footer" role="complementary" class="widgets"><?php dynamic_sidebar( 'content-footer' ); ?></div><!-- #content-footer -->
			</div><!-- #content -->
			<footer id="footer" role="contentinfo">
				<?php
					printf( __( '<a href="#document">%1$s</a> &bull; Powered by %2$s with %3$s', 'inkblot' ),
						InkblotTag::inkblot_copyright(),
						'<a href="//wordpress.org" target="_blank">WordPress</a>',
						'<a href="//github.com/mgsisk/inkblot" target="_blank">Inkblot</a>'
					);
				?>
			</footer><!-- #contentinfo -->
			<div id="page-footer" role="complementary" class="widgets"><?php dynamic_sidebar( 'page-footer' ); ?></div><!-- #page-footer -->
		</div><!-- #page -->
		<div id="document-footer" role="complementary" class="widgets"><?php dynamic_sidebar( 'document-footer' ); ?></div><!-- #document-footer -->
		<?php wp_footer(); ?>
	</body><!-- #document -->
</html>