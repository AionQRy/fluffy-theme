<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package fluffy
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<header class="page-header">
				<div class="v-container">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'fluffy' ); ?></h1>
				</div>
			</header><!-- .page-header -->

			<div class="page-content">
				<div class="v-container">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'fluffy' ); ?></p>

					<?php
					get_search_form();
					// the_widget( 'WP_Widget_Recent_Posts' );
				
					/* translators: %1$s: smiley */
					// $fluffy_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'fluffy' ), convert_smilies( ':)' ) ) . '</p>';
					// the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$fluffy_archive_content" );
					//
					// the_widget( 'WP_Widget_Tag_Cloud' );
					?>
				</div>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
