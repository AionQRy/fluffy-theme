<?php
/**
 * fluffy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package fluffy
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}


add_action( 'wp_footer', 'single_product_ajax_add_to_cart_js_script' );
function single_product_ajax_add_to_cart_js_script() {
    ?>
    <script>
    (function($) {
        $('form.cart').on('submit', function(e) {
            e.preventDefault();

            var form   = $(this),
                mainId = form.find('.single_add_to_cart_button').val(),
                fData  = form.serializeArray();

            form.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

            if ( mainId === '' ) {
                mainId = form.find('input[name="product_id"]').val();
            }

            if ( typeof wc_add_to_cart_params === 'undefined' )
                return false;

            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'custom_add_to_cart' ),
                data : {
                    'product_id': mainId,
                    'form_data' : fData
                },
                success: function (response) {
                    $(document.body).trigger("wc_fragment_refresh");
                    $('.woocommerce-error,.woocommerce-message').remove();
                    $('input[name="quantity"]').val(1);
                    $('.content-area').before(response);
                    form.unblock();
                    // console.log(response);
                },
                error: function (error) {
                    form.unblock();
                    // console.log(error);
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}

add_action( 'wc_ajax_custom_add_to_cart', 'custom_add_to_cart_handler' );
add_action( 'wc_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart_handler' );
function custom_add_to_cart_handler() {
    if( isset($_POST['product_id']) && isset($_POST['form_data']) ) {
        $product_id = $_POST['product_id'];

        $variation = $cart_item_data = $custom_data = array(); // Initializing
        $variation_id = 0; // Initializing

        foreach( $_POST['form_data'] as $values ) {
            if ( strpos( $values['name'], 'attributes_' ) !== false ) {
                $variation[$values['name']] = $values['value'];
            } elseif ( $values['name'] === 'quantity' ) {
                $quantity = $values['value'];
            } elseif ( $values['name'] === 'variation_id' ) {
                $variation_id = $values['value'];
            } elseif ( $values['name'] !== 'add_to_cart' ) {
                $custom_data[$values['name']] = esc_attr($values['value']);
            }
        }

        $product = wc_get_product( $variation_id ? $variation_id : $product_id );

        // Allow product custom fields to be added as custom cart item data from $custom_data additional array variable
        $cart_item_data = (array) apply_filters( 'woocommerce_add_cart_item_data', $cart_item_data, $product_id, $variation_id, $quantity, $custom_data );

        // Add to cart
        $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );

        if ( $cart_item_key ) {
            // Add to cart successful notice
            wc_add_notice( sprintf(
                '<a href="%s" class="button wc-forward">%s</a> %d &times; "%s" %s' ,
                wc_get_cart_url(),
                __("View cart", "woocommerce"),
                $quantity,
                $product->get_name(),
                __("has been added to your cart", "woocommerce")
            ) );
        }

        wc_print_notices(); // Return printed notices to jQuery response.
        wp_die();
    }
}

//
// add_action( 'woocommerce_after_add_to_cart_quantity', 'ts_quantity_plus_sign' );
//
// function ts_quantity_plus_sign() {
//    echo '<button type="button" class="plus yp-btn-qty" >+</button>';
// }
//
// add_action( 'woocommerce_before_add_to_cart_quantity', 'ts_quantity_minus_sign' );
// function ts_quantity_minus_sign() {
//    echo '<button type="button" class="minus yp-btn-qty" >-</button>';
// }

add_action( 'wp_footer', 'ts_quantity_plus_minus' );

function ts_quantity_plus_minus() {
   // To run this on the single product page
//    if ( ! is_product() ) return;
   ?>
   <script type="text/javascript">

      jQuery(document).ready(function($){

            $('form.cart').on( 'click', 'button.plus, button.minus', function() {

            // Get current quantity values
            var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
            var val   = parseFloat(qty.val());
            var max = parseFloat(qty.attr( 'max' ));
            var min = parseFloat(qty.attr( 'min' ));
            var step = parseFloat(qty.attr( 'step' ));

            // Change the value if plus or minus
            if ( $( this ).is( '.plus' ) ) {
               if ( max && ( max <= val ) ) {
                  qty.val( max );
               }
            else {
               qty.val( val + step );
                 }
            }
            else {
               if ( min && ( min >= val ) ) {
                  qty.val( min );
               }
               else if ( val > 1 ) {
                  qty.val( val - step );
               }
            }

         });

      });

   </script>
   <?php
}

function yp_product_option(){
global $product;

if ($product->is_type( 'variable' )) {



	$attributes = $product->get_attributes();
	?>


	<?php
	foreach ( $attributes as $attribute ):
    $attribute_data = $attribute->get_data();
		// $attribute_name = $attribute_data['name']; // The taxonomy slug name
    $attribute_terms = $attribute_data['options']; // The terms Ids
		$attribute_terms = $attribute->get_terms();

		?>
			<ul class="single-attributes-list">
		<?php foreach ($attribute_terms as  $value): ?>
			<li data-id="<?php echo $value->slug; ?>"><?php echo $value->name; ?></li>
		<?php endforeach; ?>
		</ul>

		<div class="yp-meta-info">
			<h4>
				จำนวน :
					<span>
					<?php
					if (get_field('product_unit') != '') {
					the_field('product_unit');
					}
					else {
					echo "-";
					}
				 ?></span>
			</h4>
		</div>
<?php
endforeach;
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('ul.single-attributes-list li').click(function() {
			$('ul.single-attributes-list li').removeClass('active');
			$(this).addClass('active');
			var data = $(this).attr('data-id');

			$('#pa_color').val(data).change();
		});
	});
</script>
<?php
}

}
add_action('woocommerce_before_variations_form','yp_product_option', 10 );




function is_elementor() {
	if ( function_exists( 'elementor_load_plugin_textdomain' ) )
	{
	    global $post;
	    return \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID);
	}
}
add_action( 'init', 'is_elementor' );


function rocket_lazyload_exclude_class( $attributes ) {
	$attributes[] = 'class="custom-logo"';
	return $attributes;
}
add_filter( 'rocket_lazyload_excluded_attributes', 'rocket_lazyload_exclude_class' );


add_action('wp_head', function() { echo '<script type="text/javascript"> if (typeof(wp) == "undefined") { window.wp = { i18n: { setLocaleData: (function() { return false; })} }; } </script>'; });

function vecular_prefix_menu_arrow($item_output, $item, $depth, $args) {
		if (in_array('menu-item-has-children', $item->classes)) {
				$arrow = '<div class="wrap-toggle-mobile"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></div>'; // Change the class to your font icon
				$item_output = str_replace('</a>', '</a>'. $arrow .'', $item_output);
		}
		return $item_output;
}
add_filter('walker_nav_menu_start_el', 'vecular_prefix_menu_arrow', 10, 4);



add_filter( 'woocommerce_single_product_carousel_options', 'cuswoo_update_woo_flexslider_options' );
/**
 * Filer WooCommerce Flexslider options - Add Navigation Arrows
 */
function cuswoo_update_woo_flexslider_options( $options ) {

    $options['directionNav'] = true;
		$options['sync'] = '.flex-control-thumbs';
    return $options;
}

add_filter( 'woocommerce_output_related_products_args', 'custom_woo_related_products_limit', 20 );
function custom_woo_related_products_limit( $args ) {
	$args['posts_per_page'] = 5;
	$args['columns'] = 5;
	return $args;
}


if ( ! function_exists( 'fluffy_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function fluffy_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on fluffy, use a find and replace
		 * to change 'fluffy' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'fluffy', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support('woocommerce');

		// add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', 'fluffy' ),
				'mobile' => esc_html__( 'Mobile Menu', 'fluffy' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'fluffy_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'fluffy_setup' );





/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fluffy_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fluffy_content_width', 640 );
}
add_action( 'after_setup_theme', 'fluffy_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fluffy_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'fluffy' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'fluffy' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'fluffy_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function fluffy_scripts() {
	wp_enqueue_style( 'fluffy-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'fluffy-style', 'rtl', 'replace' );
	wp_enqueue_style( 'fluffy-main', get_template_directory_uri(). '/css/main.css', array(), '1.0' );
	wp_enqueue_style( 'fluffy-child-style', get_template_directory_uri(). '/css/child-style.css', array(), '1.0' );
	wp_enqueue_script( 'jsxp-script', get_template_directory_uri() . '/js/style.js', array('jquery'), true );
   wp_enqueue_style( 'fluffy-fonts', get_template_directory_uri().'/fonts/noto-sans-thai/font.css'  );
	wp_enqueue_script( 'vecular-script', get_template_directory_uri() . '/js/vecular.js', array('jquery'), _S_VERSION, true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fluffy_scripts' );


function get_excerpt($limit, $source = null){

    $excerpt = $source == "content" ? get_the_content() : get_the_excerpt();
    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, $limit);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
    // $excerpt = $excerpt.'... <a href="'.get_permalink($post->ID).'">'.esc_html__( 'Read More', 'fluffy' ).'</a>';
		  $excerpt = $excerpt.'...';
    return $excerpt;
}


// Enable media_library_infinite_scrolling
class Enable_Media_Library_Infinite_Scrolling {
	public function __construct() {
		$this->add_hooks();
	}
	public function add_hooks() {
		add_filter( 'media_library_infinite_scrolling', '__return_true' );
	}
}
new Enable_Media_Library_Infinite_Scrolling();

 // Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



function vc_product_tab() {
	ob_start();
	?>
	<div class="vc_product_tab" data-nav="vc_product_tab">
		  <div class="nav-sub-term-yp">
				<ul>
					<li data-id="vTab_1" data-nav="vc_product_tab" class="active">หมวดหมู่</li>
					<li data-id="vTab_2" data-nav="vc_product_tab">สินค้าโปรโมชั่น</li>
					<li data-id="vTab_3" data-nav="vc_product_tab">สินค้าขายดี</li>
					<li data-id="vTab_4" data-nav="vc_product_tab">สินค้าแนะนำ</li>
				</ul>
			</div>

			<div class="content-post-tab-yp active" data-id="vTab_1">

				<div class="wrap-cat-loop">
					<?php
					$terms = get_terms( array(
							'taxonomy' => 'product_cat',
							'exclude' => array( 29 ),
							'parent'   => 0
					) );
					 ?>
					 <?php foreach ($terms as $cat): ?>
						<div class="item-cat-loop">
							<a href="<?php  echo get_term_link($cat->slug, 'product_cat'); ?>">
								<div class="wrap_thumb_cat">
									<?php
									// get the thumbnail id using the queried category term_id
								    $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
								    // get the image URL
								    $image = wp_get_attachment_url( $thumbnail_id );
									 ?>
										<img src="<?php echo $image;; ?>" alt="<?php echo $cat->name; ?>">
								</div>
								<div class="wrap_cat_info">
									<h4><?php echo $cat->name; ?></h4>
								</div>
							</a>
						</div>
					 <?php endforeach; ?>
				</div>

			</div>


			<div class="content-post-tab-yp" data-id="vTab_2">

				<div class="woocommerce">
					<ul class="products products-loop col-5">
							<?php
								$args = array(
									'post_type' => 'product',
									'tax_query'         => array(
											array(
													'taxonomy'  => 'product_cat',
													'field'     => 'id',
													'terms'     => 29
											)
										),
									'posts_per_page' => 4
									);
								$loop = new WP_Query( $args );
								$term_link = get_term( 29, 'product_cat' );
								if ( $loop->have_posts() ) {
									while ( $loop->have_posts() ) : $loop->the_post();
										wc_get_template_part( 'content', 'product' );
									endwhile;
								} else {
									echo __( 'No products found' );
								}
								wp_reset_postdata();
							?>
							<li class="view-all-proudct">
								<a href="<?php echo get_term_link($term_link); ?>">
									<span><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
									ดูสินค้าเพิ่มเติม
								</a>
							</li>
						</ul>
				</div>

			</div>


			<div class="content-post-tab-yp" data-id="vTab_3">

				<div class="woocommerce">
					<ul class="products products-loop col-5">
							<?php
								$args = array(
									'post_type' => 'product',
									'ignore_sticky_posts' => 1,
					        'meta_key' => 'total_sales',
					        'orderby' => 'meta_value_num',
					        'order' => 'DESC',
									'posts_per_page' => 5
									);
								$loop = new WP_Query( $args );
								if ( $loop->have_posts() ) {
									while ( $loop->have_posts() ) : $loop->the_post();
										wc_get_template_part( 'content', 'product' );
									endwhile;
								} else {
									echo __( 'No products found' );
								}
								wp_reset_postdata();
							?>
						</ul>
				</div>

			</div>

			<div class="content-post-tab-yp" data-id="vTab_4">

				<div class="woocommerce">
					<ul class="products products-loop col-5">
							<?php
								// The tax query
								$tax_query[] = array(
								    'taxonomy' => 'product_visibility',
								    'field'    => 'name',
								    'terms'    => 'featured',
								    'operator' => 'IN', // or 'NOT IN' to exclude feature products
								);


								$args = array(
									'post_type' => 'product',
									'ignore_sticky_posts' => 1,
									'order' => 'DESC',
									'tax_query' => $tax_query,
									'posts_per_page' => 5
									);
								$loop = new WP_Query( $args );
								if ( $loop->have_posts() ) {
									while ( $loop->have_posts() ) : $loop->the_post();
										wc_get_template_part( 'content', 'product' );
									endwhile;
								} else {
									echo __( 'No products found' );
								}
								wp_reset_postdata();
							?>
						</ul>
				</div>

			</div>


	</div>
	<?php
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode('vc_product_tab','vc_product_tab');
