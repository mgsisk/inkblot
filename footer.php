<?php
/**
 * Footer template.
 * 
 * @package Inkblot
 */
?>
				<?php print inkblot_widgetized('content-footer'); ?>
				
			</div><!-- #content.content -->
			<footer role="contentinfo" class="contentinfo widgets columns-<?php print inkblot_count_widgets('site-footer'); ?>">
				
				<?php
					if ( ! dynamic_sidebar('site-footer')) :
						printf(__('%1$s &bull; Powered by %2$s with %3$s', 'inkblot'),
							'<a href="#document">' . inkblot_copyright() . '</a>',
							'<a href="//wordpress.org">WordPress</a>',
							'<a href="//github.com/mgsisk/inkblot">Inkblot</a>'
						);
					endif;
				?>
				
			</footer><!-- .contentinfo -->
			
			<?php print inkblot_widgetized('page-footer'); ?>
			
		</div><!-- .wrapper -->
		
		<?php
			print inkblot_widgetized('document-footer');
			
			wp_footer();
		?>
		
	</body><!-- #document -->
</html>