<?php
/**
 * Footer template.
 * 
 * @package Inkblot
 */
?>
				<?php print inkblot_widgetized('content-footer'); ?>
			</div><!-- #content -->
			<footer id="footer" role="contentinfo">
				
				<?php
					printf(__('%1$s &bull; Powered by %2$s with %3$s', 'inkblot'),
						'<a href="#document">' . inkblot_copyright() . '</a>',
						'<a href="//wordpress.org" target="_blank">WordPress</a>',
						'<a href="//github.com/mgsisk/inkblot" target="_blank">Inkblot</a>'
					);
				?>
				
			</footer><!-- #footer -->
			<?php print inkblot_widgetized('page-footer'); ?>
		</div><!-- #page -->
		
		<?php
			print inkblot_widgetized('document-footer');
			
			wp_footer();
		?>
		
	</body><!-- #document -->
</html>