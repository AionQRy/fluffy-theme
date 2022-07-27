<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package fluffy
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="entry-header">
				<h1 class="page-title"><?php
				printf( esc_html__( 'Search Results for: %s', 'fluffy' ), '<span>' . get_search_query() . '</span>' );
				?></h1>
			</header><!-- .page-header -->


	<div class="v-container">
			<div class="v-post-loop -card">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				// get_template_part( 'template-parts/content', get_post_type() );
					get_template_part( 'template-parts/content','card');

			endwhile;


		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
		</div>

		<?php fluffy_posts_navigation();  ?>
	</div>

	</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
