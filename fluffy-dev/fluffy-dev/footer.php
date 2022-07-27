<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fluffy
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="v-container">

		<div class="site-info">
			<?php if (get_locale() == 'th'): ?>
				<p>© <?php echo date('Y') ?> <?php echo get_bloginfo(); ?>. <?php esc_html_e( 'All rights reserved.', 'fluffy' ); ?></p>
				<?php else: ?>
					<p>สงวนลิขสิทธิ์ <?php echo date('Y') ?> © <?php echo get_bloginfo(); ?>.</p>
			<?php endif; ?>
		</div><!-- .site-info -->
	</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
