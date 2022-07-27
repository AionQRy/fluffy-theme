<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fluffy
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'fluffy' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="v-container">
			<div class="site-branding">
					<?php the_custom_logo(); ?>
			</div><!-- .site-branding -->
		<nav id="site-navigation" class="main-navigation">

			<div id="toggle-main-menu" class="_mobile hamburger hamburger--slider">
        <div class="hamburger-box">
          <div class="hamburger-inner"></div>
        </div>
      </div>

		<div class="desktop_menu _desktop">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
				)
			);
			?>
		</div>

		<div class="overlay_menu_m"></div>
			<div id="mobile_menu_wrap">


				<div id="close-mobile-menu" class="hamburger hamburger--slider is-active">
					<div class="hamburger-box">
						<div class="hamburger-inner"></div>
					</div>
				</div>

				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mobile',
						'menu_id'        => 'mobile-menu',
					)
				);
				?>
			</div>
		</nav><!-- #site-navigation -->
		<div class="toggle-search">
			<div class="toggle-icon">
				<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
			</div>
		</div>
		</div>
	</header><!-- #masthead -->

	<div class="popup_search">
		<div class="box-search">
			<form action="get" class="search-panel">
				<div class="main-object">
					<div class="object-1">
						<input type="text" name="s" id="input-search">
					</div>
					<div class="object-2">
						<button type="submit" class="btn-search_f"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
					</div>
				</div>
			</form>
			<div class="cancel-btn_search">
				<div class="icon_cancel">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
				</div>
			</div>
		</div>
	</div>
