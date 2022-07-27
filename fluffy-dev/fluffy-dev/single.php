<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package fluffy
 */

get_header();
?>

	<main id="primary" class="site-main">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="v-container">
		<?php
		while ( have_posts() ) :
		the_post();
		?>

		<div class="entry-content">
			<div class="<?php if ( !is_elementor() ) { echo 'v-container'; 	} else { echo "no-container"; }?>">
				<?php
				the_content();
				?>
			</div>
		</div><!-- .entry-content -->

	<?php
			// the_post_navigation(
			// 	array(
			// 		'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'fluffy' ) . '</span> <span class="nav-title">%title</span>',
			// 		'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'fluffy' ) . '</span> <span class="nav-title">%title</span>',
			// 	)
			// );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
	</div>

	</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
