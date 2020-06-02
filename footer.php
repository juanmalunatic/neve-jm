<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "wrapper" div and all content after.
 *
 * @package Neve
 * @since   1.0.0
 */

do_action( 'neve_before_primary_end' );
?>
		</main><!--/.neve-main-->

		<!-- Neve footer code -->
		<?php
			/*
			do_action( 'neve_after_primary' );
			if ( apply_filters( 'neve_filter_toggle_content_parts', true, 'footer' ) === true ) {
				neve_before_footer_trigger();
				do_action( 'neve_do_footer' );
				neve_after_footer_trigger();
			}
			*/
		?>
		<!-- /Neve footer code-->

		<footer id="footer">
			<div class="footer__content">
				<div class="block block__name">
					<span class="name">
						Juan Manuel Luna López
					</span>
					<a class="mail" href="mailto:lunalopez[at]gmail">
						lunalopez [at] gmail
					</a>
				</div>
				<div class="block block__social">

					<div class="social github">
						<span class="social__img st-icon-github st-shape-rounded1"></span>
						<span class="social__link">
							<a href="https://github.com/juanmalunatic">
								juanmalunatic
							</a>
						</span>
					</div>

					<div class="social linkedin">
						<span class="social__img st-icon-linkedin st-shape-rounded1"></span>
						<span class="social__link">
							<a href="https://linkedin.com/juanmaluna">
								juanmaluna
							</a>
						</span>
					</div>
				</div>
				<div class="block block__info">
					<span class="attribution">
						Theme based on <a href="https://themeisle.com/themes/neve/">Neve</a>.
						<br />
						Powered by <a href="https://wordpress.org/">WordPress</a>.
					</span>
				</div>
			</div>
		</footer>

	</div><!--/.wrapper-->
	<?php wp_footer(); ?>

</body>

</html>
