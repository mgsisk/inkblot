<?php /** Displays the full-size webcomic on the home and single-post pages. */ global $inkblot; ?>

<div class="webcomic full">
	<div id="webcomic-above" class="widgetized"><?php dynamic_sidebar( 'inkblot-webcomic-above' ); ?></div>
	<nav class="above"><?php first_webcomic_link(); previous_webcomic_link(); random_webcomic_link(); next_webcomic_link(); last_webcomic_link(); ?></nav>
	<?php the_webcomic_object( 'full', $inkblot->option( 'single_webcomic_link' ) ); ?>
	<nav class="below"><?php first_webcomic_link(); previous_webcomic_link(); random_webcomic_link(); next_webcomic_link(); last_webcomic_link(); ?></nav>
	<div id="webcomic-below" class="widgetized"><?php dynamic_sidebar( 'inkblot-webcomic-below' ); ?></div>
</div>