<?php

// theme textdomain - must be loaded before redux
load_theme_textdomain( 'houseofcoffee', get_template_directory() . '/languages' );

/******************************************************************************/
/***************************** Theme Options **********************************/
/******************************************************************************/

if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/settings/redux/ReduxCore/framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/settings/redux/ReduxCore/framework.php' );
}
if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/settings/houseofcoffee.config.php' ) ) {
    require_once( dirname( __FILE__ ) . '/settings/houseofcoffee.config.php' );
}

global $houseofcoffee_theme_options;

// frontend presets
if (isset($_GET["preset"])) { 
	$preset = $_GET["preset"];
} else {
	$preset = "";
}

if ($preset != "") {
	if ( file_exists( dirname( __FILE__ ) . '/_presets/'.$preset.'.json' ) ) {
	$theme_options_json = file_get_contents( dirname( __FILE__ ) . '/_presets/'.$preset.'.json' );
	$houseofcoffee_theme_options = json_decode($theme_options_json, true);
	}
}


/******************************************************************************/
/******************************** Includes ************************************/
/******************************************************************************/

//if ( is_admin() ) { include_once (TEMPLATEPATH . '/inc/update-notifier.php'); }

//Include Custom Posts
include_once('inc/custom-posts/portfolio.php');



include_once('inc/custom-styles/custom-styles.php'); // Load Custom Styles
include_once('inc/templates/post-meta.php'); // Load Post meta template
include_once('inc/templates/template-tags.php'); // Load Template Tags

include_once('inc/widgets/social-media.php'); // Load Widget Social Media



//Include Shortcodes
include_once('inc/shortcodes/product-categories.php');
include_once('inc/shortcodes/socials.php');
include_once('inc/shortcodes/from-the-blog.php');
include_once('inc/shortcodes/google-map.php');
include_once('inc/shortcodes/banner.php');
include_once('inc/shortcodes/icon-box.php');
include_once('inc/shortcodes/portfolio.php');
include_once('inc/shortcodes/add-to-cart.php');
include_once('inc/shortcodes/wc-mod-product.php');



//Include Metaboxes
include_once('inc/metaboxes/page.php');
include_once('inc/metaboxes/post.php');
include_once('inc/metaboxes/portfolio.php');
include_once('inc/metaboxes/product.php');


//Custom Menu
include_once('inc/custom-menu/custom-menu.php');




/******************************************************************************/
/************************ Plugin recommendations ******************************/
/******************************************************************************/

require_once dirname( __FILE__ ) . '/inc/tgm/class-tgm-plugin-activation.php';
require_once dirname( __FILE__ ) . '/inc/tgm/plugins.php';





/******************************************************************************/
/*************************** Visual Composer **********************************/
/******************************************************************************/

if (class_exists('WPBakeryVisualComposerAbstract')) {
	
	add_action( 'init', 'visual_composer_stuff' );
	function visual_composer_stuff() {
		
		//enable vc on post types
		if(function_exists('vc_set_default_editor_post_types')) vc_set_default_editor_post_types( array('post','page','product','portfolio') );
		
		if(function_exists('vc_set_as_theme')) vc_set_as_theme(true);
		vc_disable_frontend();
		
		// Modify and remove existing shortcodes from VC
		include_once('inc/shortcodes/visual-composer/custom_vc.php');
		
		// VC Templates
		$vc_templates_dir = get_template_directory() . '/inc/shortcodes/visual-composer/vc_templates/';
		vc_set_template_dir($vc_templates_dir);
		
		// Add new shortcodes to VC
		include_once('inc/shortcodes/visual-composer/from-the-blog.php');
		include_once('inc/shortcodes/visual-composer/social-media-profiles.php');
		include_once('inc/shortcodes/visual-composer/google-map.php');
		include_once('inc/shortcodes/visual-composer/banner.php');
		include_once('inc/shortcodes/visual-composer/icon-box.php');
		include_once('inc/shortcodes/visual-composer/portfolio.php');
		
		// Add new Shop shortcodes to VC
		if (class_exists('WooCommerce')) {
			include_once('inc/shortcodes/visual-composer/wc-recent-products.php');
			include_once('inc/shortcodes/visual-composer/wc-featured-products.php');
			include_once('inc/shortcodes/visual-composer/wc-products-by-category.php');
			include_once('inc/shortcodes/visual-composer/wc-products-by-attribute.php');
			include_once('inc/shortcodes/visual-composer/wc-product-by-id-sku.php');
			include_once('inc/shortcodes/visual-composer/wc-products-by-ids-skus.php');
			include_once('inc/shortcodes/visual-composer/wc-sale-products.php');
			include_once('inc/shortcodes/visual-composer/wc-top-rated-products.php');
			include_once('inc/shortcodes/visual-composer/wc-best-selling-products.php');
			include_once('inc/shortcodes/visual-composer/wc-add-to-cart-button.php');
			include_once('inc/shortcodes/visual-composer/wc-product-categories.php');
			include_once('inc/shortcodes/visual-composer/wc-product-categories-grid.php');
		}
		
		// Remove vc_teaser
		if (is_admin()) :
			function remove_vc_teaser() {
				remove_meta_box('vc_teaser', '' , 'side');
			}
			add_action( 'admin_head', 'remove_vc_teaser' );
		endif;
	
	}

}



/******************************************************************************/
/****************************** Ajax url **************************************/
/******************************************************************************/

add_action('wp_head','houseofcoffee_ajaxurl');
function houseofcoffee_ajaxurl() {
?>
    <script type="text/javascript">
        var houseofcoffee_ajaxurl = '<?php echo admin_url('admin-ajax.php', 'relative'); ?>';
    </script>
<?php
}

/******************************************************************************/
/************************ Ajax calls ******************************************/
/******************************************************************************/

function refresh_dynamic_contents() {
	global $woocommerce, $yith_wcwl;
    $data = array(
        'cart_count_products' => class_exists('WooCommerce') ? $woocommerce->cart->cart_contents_count : 0,
        'wishlist_count_products' => class_exists('YITH_WCWL') ? yith_wcwl_count_products() : 0,
    );
	wp_send_json($data);
}
add_action( 'wp_ajax_refresh_dynamic_contents', 'refresh_dynamic_contents' );
add_action( 'wp_ajax_nopriv_refresh_dynamic_contents', 'refresh_dynamic_contents' );






/******************************************************************************/
/*********************** houseofcoffee setup *************************************/
/******************************************************************************/


if ( ! function_exists( 'houseofcoffee_setup' ) ) :
function houseofcoffee_setup() {
	
	global $houseofcoffee_theme_options;
	
	/** Theme support **/
	add_theme_support( 'menus' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce');
	function custom_header_custom_bg() {
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background' );
	}
   	
	add_post_type_support('page', 'excerpt');
	
	
	/** Add Image Sizes **/
	$shop_catalog_image_size = get_option( 'shop_catalog_image_size' );
	$shop_single_image_size = get_option( 'shop_single_image_size' );
	add_image_size('product_small_thumbnail', (int)$shop_catalog_image_size['width']/3, (int)$shop_catalog_image_size['height']/3, isset($shop_catalog_image_size['crop']) ? true : false); // made from shop_catalog_image_size
	add_image_size('shop_single_small_thumbnail', (int)$shop_single_image_size['width']/3, (int)$shop_single_image_size['height']/3, isset($shop_catalog_image_size['crop']) ? true : false); // made from shop_single_image_size
	add_image_size( 'blog-isotope', 620, 500, true ); 
	
	/** Register menus **/	
	register_nav_menus( array(
		'top-bar-navigation' => __( 'Top Bar Navigation', 'houseofcoffee' ),
		'main-navigation' => __( 'Main Navigation', 'houseofcoffee' ),
		'footer-navigation' => __( 'Footer Navigation', 'houseofcoffee' ),
	) );
	
	if ( (isset($houseofcoffee_theme_options['main_header_off_canvas'])) && (trim($houseofcoffee_theme_options['main_header_off_canvas']) == "1" ) ) {
		register_nav_menus( array(
			'secondary_navigation' => __( 'Secondary Navigation (Off-Canvas)', 'houseofcoffee' ),
		) );
	}
	
	if ( (isset($houseofcoffee_theme_options['main_header_layout'])) && ( $houseofcoffee_theme_options['main_header_layout'] == "2" ) ) {
		register_nav_menus( array(
			'centered_header_left_navigation' => __( 'Centered Header - Left Navigation', 'houseofcoffee' ),
			'centered_header_right_navigation' => __( 'Centered Header - Right Navigation', 'houseofcoffee' ),
		) );
	}
	
	/** WooCommerce Number of products displayed per page **/	
	if ( (isset($houseofcoffee_theme_options['products_per_page'])) ) {
		add_filter( 'loop_shop_per_page', create_function( '$cols', 'return ' . $houseofcoffee_theme_options['products_per_page'] . ';' ), 20 );
	}

}
endif; // houseofcoffee_setup
add_action( 'after_setup_theme', 'houseofcoffee_setup' );

/******************************************************************************/
/**************************** Enqueue styles **********************************/
/******************************************************************************/

// frontend
function houseofcoffee_styles() {
	
	global $houseofcoffee_theme_options;

	wp_enqueue_style('houseofcoffee-foundation-app', get_template_directory_uri() . '/css/app.css', array(), '5.3.1', 'all' );		
	
	wp_enqueue_style('houseofcoffee-animate', get_template_directory_uri() . '/css/animate.css', array(), '2.0', 'all' );
	
	wp_enqueue_style('houseofcoffee-font-awesome', get_template_directory_uri() . '/inc/fonts/font-awesome/css/font-awesome.min.css', array(), '4.0.3', 'all' );
	wp_enqueue_style('houseofcoffee-font-linea-arrows', get_template_directory_uri() . '/inc/fonts/linea-fonts/arrows/styles.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-font-linea-basic', get_template_directory_uri() . '/inc/fonts/linea-fonts/basic/styles.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-font-linea-basic_elaboration', get_template_directory_uri() . '/inc/fonts/linea-fonts/basic_elaboration/styles.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-font-linea-ecommerce', get_template_directory_uri() . '/inc/fonts/linea-fonts/ecommerce/styles.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-font-linea-music', get_template_directory_uri() . '/inc/fonts/linea-fonts/music/styles.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-font-linea-software', get_template_directory_uri() . '/inc/fonts/linea-fonts/software/styles.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-font-linea-weather', get_template_directory_uri() . '/inc/fonts/linea-fonts/weather/styles.css', array(), '1.0', 'all' );	
	wp_enqueue_style('houseofcoffee-fresco', get_template_directory_uri() . '/css/fresco/fresco.css', array(), '1.3.0', 'all' );
	wp_enqueue_style('houseofcoffee-idangerous-swiper', get_template_directory_uri() . '/css/idangerous.swiper.css', array(), '2.3', 'all' );
	wp_enqueue_style('houseofcoffee-owl', get_template_directory_uri() . '/css/owl.carousel.css', array(), '1.3.1', 'all' );
	wp_enqueue_style('houseofcoffee-owl-theme', get_template_directory_uri() . '/css/owl.theme.css', array(), '1.3.1', 'all' );
	wp_enqueue_style('houseofcoffee-offcanvas', get_template_directory_uri() . '/css/offcanvas.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-nanoscroller', get_template_directory_uri() . '/css/nanoscroller.css', array(), '0.7.6', 'all' );
	wp_enqueue_style('houseofcoffee-select2', get_template_directory_uri() . '/css/select2.css', array(), '3.4.5', 'all' );
	wp_enqueue_style('houseofcoffee-easyzoom', get_template_directory_uri() . '/css/easyzoom.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-defaults', get_template_directory_uri() . '/css/defaults.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-woocommerce-overwrite', get_template_directory_uri() . '/css/woocommerce-overwrite.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-top-bar', get_template_directory_uri() . '/css/header-topbar.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-headers', get_template_directory_uri() . '/css/headers.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-menus', get_template_directory_uri() . '/css/navigations.css', array(), '1.0', 'all' );
	
	if ( isset($houseofcoffee_theme_options['main_header_layout']) ) {		
		if ( $houseofcoffee_theme_options['main_header_layout'] == "1" ) {
			wp_enqueue_style('houseofcoffee-header-default', get_template_directory_uri() . '/css/header-default.css', array(), '1.0', 'all' );
		} 		
		elseif ( $houseofcoffee_theme_options['main_header_layout'] == "2" ) {
			wp_enqueue_style('houseofcoffee-header-centered-2menus', get_template_directory_uri() . '/css/header-centered-2menus.css', array(), '1.0', 'all' );
		}
		elseif ( $houseofcoffee_theme_options['main_header_layout'] == "3" ) {
			wp_enqueue_style('houseofcoffee-header-centered-menu-under', get_template_directory_uri() . '/css/header-centered-menu-under.css', array(), '1.0', 'all' );
		} 		
	}		
	else {	
		wp_enqueue_style('houseofcoffee-header-default', get_template_directory_uri() . '/css/header-default.css', array(), '1.0', 'all' );	
	}
	
	if (isset($houseofcoffee_theme_options['font_source']) && ($houseofcoffee_theme_options['font_source'] == "2")) {
		if ( (isset($houseofcoffee_theme_options['font_google_code'])) && ($houseofcoffee_theme_options['font_google_code'] != "") ) {
			wp_enqueue_style('houseofcoffee-font_google_code', $houseofcoffee_theme_options['font_google_code'], array(), '1.0', 'all' );
		}
	}
	
	wp_enqueue_style('houseofcoffee-styles', get_template_directory_uri() . '/css/styles.css', array(), '1.0', 'all' );
	wp_enqueue_style('houseofcoffee-responsive', get_template_directory_uri() . '/css/responsive.css', array(), '1.0', 'all' );

	wp_enqueue_style('houseofcoffee-default-style', get_stylesheet_uri());

}
add_action( 'wp_enqueue_scripts', 'houseofcoffee_styles', 99 );



// admin area
function houseofcoffee_admin_styles() {
    if ( is_admin() ) {
        
		wp_enqueue_style("wp-color-picker");
		wp_enqueue_style("houseofcoffee_admin_styles", get_template_directory_uri() . "/css/wp-admin-custom.css", false, "1.0", "all");
		
		if (class_exists('WPBakeryVisualComposerAbstract')) { 
			wp_enqueue_style('houseofcoffee_visual_composer', get_template_directory_uri() .'/css/visual-composer.css', false, "1.0", 'all');
			wp_enqueue_style('houseofcoffee-font-linea-arrows', get_template_directory_uri() . '/inc/fonts/linea-fonts/arrows/styles.css', false, '1.0', 'all' );
			wp_enqueue_style('houseofcoffee-font-linea-basic', get_template_directory_uri() . '/inc/fonts/linea-fonts/basic/styles.css', false, '1.0', 'all' );
			wp_enqueue_style('houseofcoffee-font-linea-basic_elaboration', get_template_directory_uri() . '/inc/fonts/linea-fonts/basic_elaboration/styles.css', false, '1.0', 'all' );
			wp_enqueue_style('houseofcoffee-font-linea-ecommerce', get_template_directory_uri() . '/inc/fonts/linea-fonts/ecommerce/styles.css', false, '1.0', 'all' );
			wp_enqueue_style('houseofcoffee-font-linea-music', get_template_directory_uri() . '/inc/fonts/linea-fonts/music/styles.css', false, '1.0', 'all' );
			wp_enqueue_style('houseofcoffee-font-linea-software', get_template_directory_uri() . '/inc/fonts/linea-fonts/software/styles.css', false, '1.0', 'all' );
			wp_enqueue_style('houseofcoffee-font-linea-weather', get_template_directory_uri() . '/inc/fonts/linea-fonts/weather/styles.css', false, '1.0', 'all' );
		}
    }
}
add_action( 'admin_enqueue_scripts', 'houseofcoffee_admin_styles' );



add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

	$tabs['description']['title'] = __( 'Did You Know' );		// Rename the description tab

	return $tabs;

}



/******************************************************************************/
/*************************** Enqueue scripts **********************************/
/******************************************************************************/

// frontend
function houseofcoffee_scripts() {
	
	global $houseofcoffee_theme_options;
	
	/** In Header **/
	
	wp_enqueue_script('houseofcoffee-google-maps', 'https://maps.googleapis.com/maps/api/js?sensor=false', array(), '1.0', FALSE);
	
	if (isset($houseofcoffee_theme_options['font_source']) && ($houseofcoffee_theme_options['font_source'] == "3")) {
		if ( (isset($houseofcoffee_theme_options['font_typekit_kit_id'])) && ($houseofcoffee_theme_options['font_typekit_kit_id'] != "") ) {
			wp_enqueue_script('houseofcoffee-font_typekit', '//use.typekit.net/'.$houseofcoffee_theme_options['font_typekit_kit_id'].'.js', array(), NULL, FALSE );
			wp_enqueue_script('houseofcoffee-font_typekit_exec', get_template_directory_uri() . '/js/typekit.js', array(), NULL, FALSE );
		}
	}	
	
	/** In Footer **/
	
	wp_enqueue_script('houseofcoffee-touchswipe', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js', array('jquery'), '1.6.5', TRUE);
	wp_enqueue_script('houseofcoffee-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), '1.0.3', TRUE);
	wp_enqueue_script('houseofcoffee-idangerous-swiper', get_template_directory_uri() . '/js/idangerous.swiper-2.4.1.min.js', array('jquery'), '2.4.1', TRUE);
	wp_enqueue_script('houseofcoffee-owl', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), '1.3.1', TRUE);
	wp_enqueue_script('houseofcoffee-fresco', get_template_directory_uri() . '/js/fresco.js', array('jquery'), '1.3.0', TRUE);
	wp_enqueue_script('houseofcoffee-select2', get_template_directory_uri() . '/js/select2.min.js', array('jquery'), '3.5.1', TRUE);
	wp_enqueue_script('houseofcoffee-nanoscroller', get_template_directory_uri() . '/js/jquery.nanoscroller.min.js', array('jquery'), '0.7.6', TRUE);
	wp_enqueue_script('houseofcoffee-stellar', get_template_directory_uri() . '/js/jquery.stellar.min.js', array('jquery'), '0.6.2', TRUE);
	
	wp_enqueue_script('houseofcoffee-isotope', get_template_directory_uri() . '/js/isotope.pkgd.min.js', array('jquery'), 'v2.0.0', TRUE);
	wp_enqueue_script('houseofcoffee-imagesloaded', get_template_directory_uri() . '/js/imagesloaded.js', array('jquery'), 'v3.1.4', TRUE);
	
	wp_enqueue_script('houseofcoffee-easyzoom', get_template_directory_uri() . '/js/easyzoom.js', array('jquery'), '1.0', TRUE);
	
	wp_enqueue_script('houseofcoffee-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', TRUE);
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'houseofcoffee_scripts', 99 );



// admin area
function houseofcoffee_admin_scripts() {
    if ( is_admin() ) {
        global $post_type;
		
		if ( (isset($_GET['post_type']) && ($_GET['post_type'] == 'portfolio')) || ($post_type == 'portfolio')) :
			wp_enqueue_script("houseofcoffee_admin_scripts", get_template_directory_uri() . "/js/wp-admin-portfolio.js", array('wp-color-picker'), false, "1.0");
		endif;
		
    }
}
add_action( 'admin_enqueue_scripts', 'houseofcoffee_admin_scripts' );





/*********************************************************************************************/
/******************************** Tweak WP admin bar  ****************************************/
/*********************************************************************************************/

add_action( 'wp_head', 'houseofcoffee_override_toolbar_margin', 11 );
function houseofcoffee_override_toolbar_margin() {	
	if ( is_admin_bar_showing() ) {
		?>
			<style type="text/css" media="screen">
				@media only screen and (max-width: 63.9375em) {
					html { margin-top: 0 !important; }
					* html body { margin-top: 0 !important; }
				}
			</style>
		<?php 
	}
}




/*********************************************************************************************/
/******************************** Title format  **********************************************/
/*********************************************************************************************/

add_filter( 'wp_title', 'houseofcoffee_wp_title', 10, 2 );
function houseofcoffee_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'houseofcoffee' ), max( $paged, $page ) );
	}

	return $title;
}


/******************************************************************************/
/****** Register widgetized area and update sidebar with default widgets ******/
/******************************************************************************/

function houseofcoffee_widgets_init() {
	
	$sidebars_widgets = wp_get_sidebars_widgets();	
	$footer_area_widgets_counter = "0";	
	if (isset($sidebars_widgets['footer-widget-area'])) $footer_area_widgets_counter  = count($sidebars_widgets['footer-widget-area']);
	
	switch ($footer_area_widgets_counter) {
		case 0:
			$footer_area_widgets_columns ='large-12';
			break;
		case 1:
			$footer_area_widgets_columns ='large-12';
			break;
		case 2:
			$footer_area_widgets_columns ='large-6';
			break;
		case 3:
			$footer_area_widgets_columns ='large-4';
			break;
		case 4:
			$footer_area_widgets_columns ='large-3';
			break;
		default:
			$footer_area_widgets_columns ='large-3';
	}
	
	//default sidebar
	register_sidebar(array(
		'name'          => __( 'Sidebar', 'houseofcoffee' ),
		'id'            => 'default-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	//footer widget area
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'houseofcoffee' ),
		'id'            => 'footer-widget-area',
		'before_widget' => '<div class="' . $footer_area_widgets_columns . ' columns"><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	//catalog widget area
	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'houseofcoffee' ),
		'id'            => 'catalog-widget-area',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'houseofcoffee_widgets_init' );





/******************************************************************************/
/****** Remove Woocommerce prettyPhoto ***********************************************/
/******************************************************************************/

add_action( 'wp_enqueue_scripts', 'houseofcoffee_remove_woo_lightbox', 99 );
function houseofcoffee_remove_woo_lightbox() {
    wp_dequeue_script('prettyPhoto-init');
}



/******************************************************************************/
/****** Add Fresco to Galleries ***********************************************/
/******************************************************************************/

add_filter( 'wp_get_attachment_link', 'sant_prettyadd', 10, 6);
function sant_prettyadd ($content, $id, $size, $permalink, $icon, $text) {
    if ($permalink) {
    	return $content;    
    }
    $content = preg_replace("/<a/","<a class=\"fresco\" data-fresco-group=\"\"", $content, 1);
    return $content;
}



/******************************************************************************/
/* Change breadcrumb separator on woocommerce page ****************************/
/******************************************************************************/

add_filter( 'woocommerce_breadcrumb_defaults', 'jk_change_breadcrumb_delimiter' );
function jk_change_breadcrumb_delimiter( $defaults ) {
    // Change the breadcrumb delimeter from '/' to '>'  
    $defaults['delimiter'] = ' &gt; ';
    return $defaults;
}







/******************************************************************************/
/****** Add Font Awesome to Redux *********************************************/
/******************************************************************************/

function newIconFont() {

    wp_register_style(
        'redux-font-awesome',
        get_template_directory_uri() . '/inc/fonts/font-awesome/css/font-awesome.min.css',
        array(),
        time(),
        'all'
    );  
    wp_enqueue_style( 'redux-font-awesome' );
}
add_action( 'redux/page/houseofcoffee_theme_options/enqueue', 'newIconFont' );




/******************************************************************************/
/* Remove Admin Bar - Only display to administrators **************************/
/******************************************************************************/

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}




/******************************************************************************/
/* WooCommerce Update Number of Items in the cart *****************************/
/******************************************************************************/

add_action('woocommerce_ajax_added_to_cart', 'houseofcoffee_ajax_added_to_cart');
function houseofcoffee_ajax_added_to_cart() {

	add_filter('add_to_cart_fragments', 'houseofcoffee_shopping_bag_items_number');
	function houseofcoffee_shopping_bag_items_number( $fragments ) 
	{
		global $woocommerce;
		ob_start(); ?>

		<script>
		(function($){
			$('.shopping-bag-button').trigger('click');
		})(jQuery);
		</script>
        
        <span class="shopping_bag_items_number"><?php echo esc_html($woocommerce->cart->cart_contents_count); ?></span>

		<?php
		$fragments['.shopping_bag_items_number'] = ob_get_clean();
		return $fragments;
	}

}






/******************************************************************************/
/* WooCommerce Number of Related Products *************************************/
/******************************************************************************/

function woocommerce_output_related_products() {
	$atts = array(
		'posts_per_page' => '6',
		'orderby'        => 'rand'
	);
	woocommerce_related_products($atts);
}






/******************************************************************************/
/* WooCommerce Add data-src & lazyOwl to Thumbnails ***************************/
/******************************************************************************/
function woocommerce_get_product_thumbnail( $size = 'product_small_thumbnail', $placeholder_width = 0, $placeholder_height = 0  ) {
	global $post;

	if ( has_post_thumbnail() ) {
		$image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'shop_catalog' );
		return get_the_post_thumbnail( $post->ID, $size, array('data-src' => $image_src[0], 'class' => 'lazyOwl') );
		//return '<div><img data-src="' . $image_src[0] . '" class="lazyOwl"></div>';
	} elseif ( wc_placeholder_img_src() ) {
		return wc_placeholder_img( $size );
	}
}






/******************************************************************************/
/* WooCommerce Wrap Oembed Stuff **********************************************/
/******************************************************************************/
add_filter('embed_oembed_html', 'houseofcoffee_embed_oembed_html', 99, 4);
function houseofcoffee_embed_oembed_html($html, $url, $attr, $post_id) {
	return '<div class="video-container">' . $html . '</div>';
}





/******************************************************************************/
/* WooCommerce remove review tab **********************************************/
/******************************************************************************/
if ( (isset($houseofcoffee_theme_options['review_tab'])) && ($houseofcoffee_theme_options['review_tab'] == "0" ) ) {
add_filter( 'woocommerce_product_tabs', 'houseofcoffee_remove_reviews_tab', 98);
	function houseofcoffee_remove_reviews_tab($tabs) {
		unset($tabs['reviews']);
		return $tabs;
	}
}





/******************************************************************************/
/****** WooCommerce Wishlist YITH Ajax Hook ***********************************/
/******************************************************************************/

/*function wishlist_shortcode_offcanvas() {
    echo do_shortcode('[houseofcoffee_yith_wcwl_wishlist]');
    die;
}
add_action('wp_ajax_wishlist_shortcode', 'wishlist_shortcode_offcanvas');
add_action('wp_ajax_nopriv_wishlist_shortcode', 'wishlist_shortcode_offcanvas');*/



/******************************************************************************/
/****** Set woocommerce images sizes ******************************************/
/******************************************************************************/

/**
 * Hook in on activation
 */
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'houseofcoffee_woocommerce_image_dimensions', 1 );

/**
 * Define image sizes
 */
function houseofcoffee_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> '350',	// px
		'height'	=> '435',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '570',	// px
		'height'	=> '708',	// px
		'crop'		=> 1 		// true
	);

	$thumbnail = array(
		'width' 	=> '70',	// px
		'height'	=> '87',	// px
		'crop'		=> 1 		// false
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}

if ( ! isset( $content_width ) ) $content_width = 900;