					<?php inkblot_end_content(); ?>
					</div> <!-- .group -->
				</div> <!-- #body -->
				<div id="foot">
					<div class="group">
						<div class="interior">
							<div class="alignleft">&copy; <?php echo date( 'Y' ) . ' '; bloginfo( 'name' ); ?></div>
							<div class="alignright"><?php printf( __( 'Powered by <a href="%s">WordPress</a> with <a href="%s">WebComic &amp; InkBlot</a>', 'inkblot' ), 'http://wordpress.org/', 'http://maikeruon.com/wcib/' ); ?></div>
						</div>
					</div>
				</div>
				<?php get_sidebar( 'page-bottom' ); ?>
			</div> <!-- #page -->
		</div> <!-- #wrap-inner -->
	</div> <!-- #wrap-outer -->
	<?php wp_footer(); ?>
</body>
</html>