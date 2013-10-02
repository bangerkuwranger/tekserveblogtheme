<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Set Localization (do not remove) */
load_child_theme_textdomain( 'apparition', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'apparition' ) );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', __( 'Apparition Theme', 'apparition' ) );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/apparition' );

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'apparition_add_viewport_meta_tag' );
function apparition_add_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Force full width content layout */
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/** Add support for custom background */
add_theme_support( 'custom-background' );

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array(
	'apparition-green' 		=>	__( 'Green', 'apparition' ),
	'apparition-orange'		=>	__( 'Orange', 'apparition' ),
	'apparition-pink'		=>	__( 'Pink', 'apparition' ),
	'apparition-red'		=>	__( 'Red', 'apparition' ),
	'apparition-lightblue'		=>	__( 'Light Blue', 'apparition' ),
	'apparition-darkblue'		=>	__( 'Dark Blue', 'apparition' ),
) );

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array(
	'width'	=> 1140,
	'height'	=> 120
) );

/** Sets Content Width */
$content_width = 1140;

/** Remove the site description */
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'inner',
	'footer-widgets',
	'footer'
) );

/** Add new image sizes */
add_image_size( 'featured-circle', 300, 300, TRUE );
add_image_size( 'featured-square', 300, 300, TRUE );

/** Unregister layout settings */
genesis_unregister_layout( 'content-sidebar' );
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

/** Unregister secondary sidebar */
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );

/** Add odd/even post class */
function apparition_oddeven_post_class ( $classes ) {
	global $current_class;
	$classes[] = $current_class;
	$current_class = ($current_class == 'odd') ? 'even' : 'odd';
	return $classes;
}
add_filter ( 'post_class' , 'apparition_oddeven_post_class' );
global $current_class;
$current_class = 'odd';

/** Relocate the Genesis post image */
remove_action( 'genesis_post_content', 'genesis_do_post_image' );
add_action( 'genesis_before_post_title', 'genesis_do_post_image' );

/** Add the featured image section */
add_action( 'genesis_before_post_title', 'apparition_featured_image' );
function apparition_featured_image() {
	if ( is_singular( array( 'post', 'page' ) ) && has_post_thumbnail() ){
		echo '<div class="featured-image">';
		echo get_the_post_thumbnail( $thumbnail->ID, 'thumbnail-bw' );
		echo '</div>';
	}
}

/** Remove the post info function */
remove_action( 'genesis_before_post_content', 'genesis_post_info' );

/** Reposition the post meta function */
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
add_action( 'genesis_post_content', 'genesis_post_meta' );

/** Customize the post meta function */
add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
if ( !is_page() ) {
	$post_meta = '[post_date] [post_author_posts_link] [post_comments] [post_edit]';
	return $post_meta;
}}

/** Modify the size of the Gravatar in the author box */
add_filter( 'genesis_author_box_gravatar_size', 'apparition_author_box_gravatar_size' );
function apparition_author_box_gravatar_size( $size ) {
	return '85';
}

/** Customize the comment submit button text */
add_filter( 'genesis_comment_form_args', 'apparition_comment_form_args' );
function apparition_comment_form_args( $args ) {
	$args['label_submit'] = __( 'Submit', 'apparition' );
	return $args;
}

/** Modify the Genesis content limit read more link */
add_filter( 'get_the_content_more_link', 'apparition_read_more_link' );
function apparition_read_more_link() {
	return '... <a class="more-link" href="' . get_permalink() . '">' . __( '[Continue Reading]', 'apparition' ) .'</a>';
}

/** Add support for 3-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 3 );

add_action('after_setup_theme','bw_images_size');
function bw_images_size() {
	add_image_size('thumbnail-bw', 300, 300, FALSE);
}
add_filter('wp_generate_attachment_metadata','bw_images_filter');
function bw_images_filter($meta) {
	$file = wp_upload_dir();
	$file = trailingslashit($file['path']).$meta['sizes']['thumbnail-bw']['file'];
	list($orig_w, $orig_h, $orig_type) = @getimagesize($file);
	$image = wp_load_image($file);
	imagefilter($image, IMG_FILTER_GRAYSCALE);
	//imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
	switch ($orig_type) {
		case IMAGETYPE_GIF:
			$file = str_replace(".gif", "-bw.gif", $file);
			imagegif( $image, $file );
			break;
		case IMAGETYPE_PNG:
			$file = str_replace(".png", "-bw.png", $file);
			imagepng( $image, $file );
			break;
		case IMAGETYPE_JPEG:
			$file = str_replace(".jpg", "-bw.jpg", $file);
			imagejpeg( $image, $file );
			break;
	}
	return $meta;
}
add_filter( 'wp_nav_menu_items', 'genesis_search_primary_nav_menu', 10, 2 );

function genesis_search_primary_nav_menu( $menu, stdClass $args ){
        
       
        if ( 'primary' != $args->theme_location )
        	return $menu;
        
               if( genesis_get_option( 'nav_extras' ) )
                return $menu;
        
        $menu .= sprintf( '<li class="custom-search">%s</li>', __( genesis_search_form( $echo ) ) );
        
        
        return $menu;
        
}
add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'testimonial',
		array(
			'labels' => array(
				'name' => __( 'Quotes and Testimonials' ),
				'singular_name' => __( 'Testimonials' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'testimonial'),
		)
	);
}
function include_local_scripts() {
	wp_enqueue_script ( 'ui-elements', get_stylesheet_directory_uri() . '/js/ui-elements.js', array( 'jquery' ), '', true );
// 	wp_enqueue_style ( 'responsivemenucss', get_stylesheet_directory_uri() . '/css/step4.css');
	wp_enqueue_script ( 'modernizr', 'http://modernizr.com/downloads/modernizr-latest.js', array( 'jquery' ), '', true );
 	wp_enqueue_script ( 'color2color', get_stylesheet_directory_uri() . '/js/color2color.js' );

}
add_action( 'wp_enqueue_scripts', 'include_local_scripts' );
require_once get_stylesheet_directory() . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function my_theme_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin pre-packaged with a theme
		// array(
// 			'name'     				=> 'TGM Example Plugin', // The plugin name
// 			'slug'     				=> 'tgm-example-plugin', // The plugin slug (typically the folder name)
// 			'source'   				=> get_stylesheet_directory() . '/lib/plugins/tgm-example-plugin.zip', // The plugin source
// 			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
// 			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
// 			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
// 			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
// 			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
// 		),
// 
 		// This is an example of how to include a plugin from the WordPress Plugin Repository
// 		array(
// 			'name' 		=> 'BuddyPress',
// 			'slug' 		=> 'buddypress',
// 			'required' 	=> false,
// 		),

		array(
			'name' 		=> 'Advanced Custom Fields',
			'slug' 		=> 'advanced-custom-fields',
			'required' 	=> true,
			'version' 	=> '4.2.2',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Emma Emarketing Plugin for WordPress',
			'slug' 		=> 'emma-emarketing-plugin',
			'required' 	=> false,
			'version' 	=> '1.0.5',
			'force_activation' 	=> false,
		),
		
		array(
			'name' 		=> 'Genesis Simple Hooks',
			'slug' 		=> 'genesis-simple-hooks',
			'required' 	=> true,
			'version' 	=> '2.0.0',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'The Events Calendar',
			'slug' 		=> 'the-events-calendar',
			'required' 	=> true,
			'version' 	=> '3.0.3',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Use Google Libraries',
			'slug' 		=> 'use-google-libraries',
			'required' 	=> true,
			'version' 	=> '1.5.2',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Collapse-Pro-Matic',
			'slug' 		=> 'collaps-pro-matic',
			'required' 	=> true,
			'version' 	=> '0.5.0',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory() . '/lib/collapse-pro-matic-v0.5.zip'
		),

	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'apparition';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );

}
add_filter('upload_mimes', 'my_upload_mimes');
 
function my_upload_mimes($mimes = array()) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_editor_style( 'style.css' );
add_filter( 'genesis_nav_items', 'magicNav', 10, 2 );
add_filter( 'wp_nav_menu_items', 'magicNav', 10, 2 );
function magicNav($menu, stdClass $args) {
	echo "<div id='magicNav'>";
// 	$popularButton = "PopularSet";
// 	var_dump($popularButton);
	$popularButton = wpp_get_mostpopular('header=""&header_start="<span>"&header_end="</span>"&range="all"&limit=4&order_by="avg"&excerpt_length=100&post_type=post&wpp_start="<div id=\'popularTop\'><h1>POPULAR</h1><ul id=\'popularButton\'>"&wpp_end="</ul></div>"&post_html="<li><h2>{text_title}</h2><p>{summary}</p></li>"');
// 	$popularPosts = wpp_get_mostpopular('header=""&header_start="<span>"&header_end="</span>"&range="all"&post_type=post&limit=4&order_by="avg"&excerpt_length=90&thumbnail_width=120&thumbnail_height=120&stats_author=1&wpp_start="<ul id=\'popularPosts\'>"&post_html="<li><a href=\'{url}\'><div class=\'popularThumb\'>{thumb}</div><div class=\'popularArticle\'><h2>{text_title}</h2><p><strong>{author}</strong></p><p>{summary}</p></div></a></li></ul>"');
	echo "<div id='facebookTop'>";
	echo "<h1>Facebook</h1>";
	$facebookButton = recent_facebook_posts(array('likes' => 1, 'excerpt_length' => 100, 'number' => 1));
	echo "</div>";
// 	$facebookPosts = recent_facebook_posts(array('likes' => 1, 'excerpt_length' => 30, 'number' => 4));
	echo "<div id='twitterTop'>";
	echo "<h1>Twitter</h1>";
	echo do_shortcode('[get_tweet_timeline username="Tekserve" number="1" showlinks="false" newwindow="false" nofollow="true" avatar="false"]');
	echo "</div>";
	echo "</div>";
// 	$menu .="<div id='magicNav'>{$popularButton}</div>";// .$popularButton.$facebookButton."</div>";
// 	var_dump($popularButton);
	return $menu;
}