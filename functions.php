<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Set Localization (do not remove) */
load_child_theme_textdomain( 'apparition', apply_filters( 'child_theme_textdomain', get_stylesheet_directory_uri() . '/languages', 'apparition' ) );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', __( 'Apparition Theme', 'apparition' ) );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/apparition' );

/** Add HTML5 support *///anotherheadachforanotherday
// add_theme_support( 'html5' );

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'apparition_add_viewport_meta_tag' );
function apparition_add_viewport_meta_tag() {
	echo '<meta name="viewport" content="initial-scale=1">';
}

/** Force full width content layout - disabled to allow selection */
// add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

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
	'inner',
	'footer-ets',
	'footer',
	'footer-widgets'
) );

/** Remove Subnav */
remove_action( 'genesis_after_header', 'genesis_do_subnav' );

/** Genesis Single Post Navigation: Reverse link direction */
define( 'GSPN_REVERSE_LINK_DIRECTION', TRUE );

add_action('get_header', 'remove_page_titles');
function remove_page_titles() {
	if( get_post_type() == 'page' && !(is_search()) ) {
		remove_action('genesis_post_title', 'genesis_do_post_title');
	}
}

/** Add Twitter Meta to single post pages **/
add_action('wp_head','twitterMeta');

function twitterMeta() {
	if( is_single() ) {
		echo '<meta name="twitter:widgets:csp" content="on">';
	}
}


/** Add new image sizes */
add_image_size( 'featured-circle', 300, 300, TRUE );
add_image_size( 'featured-square', 300, 300, TRUE );

/** Unregister layout settings */
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

add_filter( 'get_the_content_more_link', 'search_page_read_more_link' );
function search_page_read_more_link() {
if ( is_search() ):
	return '... <a class="more-link" href="' . get_permalink() . '">See This Result</a>';
	else:
	return '... <a class="more-link" href="' . get_permalink() . '">Read More...</a>';
	endif;
}


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
add_action( 'genesis_after_post_title', 'genesis_post_meta' );

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

/** Add support for 5-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 5 );

/** Create additional thumnails in black and white */
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


/** Include JS files that create full width sections and wraps with adaptive background colors */
function include_local_scripts() {
	wp_enqueue_style ( 'apparitioncss', get_stylesheet_directory_uri() . '/apparition.css' );
 	wp_enqueue_style ( 'gspn', get_stylesheet_directory_uri() . '/gspn-additons.css' );
	wp_enqueue_script ( 'jquery-ui-core' );
//
//	debug includes
//
// 	wp_enqueue_script ( 'detailbox', get_stylesheet_directory_uri() . '/js/detailbox.js', array( 'jquery' ), '', true );
// 	wp_enqueue_script ( 'icaps', get_stylesheet_directory_uri() . '/js/icaps.js', array( 'jquery' ), '', true );
// 	wp_enqueue_script ( 'navmenu', get_stylesheet_directory_uri() . '/js/navmenu.js', array( 'jquery' ), '', true );
// 	wp_enqueue_script ( 'width', get_stylesheet_directory_uri() . '/js/width.js', array( 'jquery' ), '', true );
// 	wp_enqueue_script ( 'loadpage', get_stylesheet_directory_uri() . '/js/loadpage.js', array( 'jquery', 'detailbox', 'icaps', 'navmenu', 'width' ), '', true );
	wp_enqueue_script ( 'apparitionjs', get_stylesheet_directory_uri() . '/js/apparition.js', array( 'jquery' ) );
// 	wp_enqueue_script ( 'formtitles', get_stylesheet_directory_uri() . '/js/formtitles.js', array( 'jquery' ) );
	
	//get user id if logged in or set user id to 'guest' if not logged in
	$user_id = get_current_user_id();
	if ($user_id == 0) {
	
		$user_id = 'guest';
		
	}//	end if ($user_id == 0)

	//include url of this child theme directory and user id as js variable
	$jsdata = array(
		'cssurl'	=> get_stylesheet_directory_uri(),
		'userid'	=> $user_id
	);
	wp_localize_script( 'apparitionjs', 'themeInfo', $jsdata );
}
add_action( 'wp_enqueue_scripts', 'include_local_scripts' );

/** Include PHP class to request user include functional plugins;
(these functions were 3rd party or non-presentational) */
require_once get_stylesheet_directory() . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'apparition_tekserve_register_required_plugins' );
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
function apparition_tekserve_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

/**This is an example of how to include a plugin pre-packaged with a theme

array(
			'name'     				=> 'TGM Example Plugin', // The plugin name
			'slug'     				=> 'tgm-example-plugin', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory_uri() . '/lib/plugins/tgm-example-plugin.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),

 		// This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name' 		=> 'BuddyPress',
			'slug' 		=> 'buddypress',
			'required' 	=> false,
		), 
*/

		array(
			'name' 		=> 'AddThis Smart Layers',
			'slug' 		=> 'addthis-smart-layers',
			'required' 	=> true,
			'version' 	=> '1.0.10',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Font Awesome WP',
			'slug' 		=> 'font-awesome-wp',
			'required' 	=> true,
			'version' 	=> '1.0',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'FooTable',
			'slug' 		=> 'footable',
			'required' 	=> true,
			'version' 	=> '0.3.1',
			'force_activation' 	=> true,
		),
				
		array(
			'name' 		=> 'Genesis Simple Hooks',
			'slug' 		=> 'genesis-simple-hooks',
			'required' 	=> true,
			'version' 	=> '2.1.0',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Get to Tekserve',
			'slug' 		=> 'get-to-tekserve',
			'required' 	=> true,
			'version' 	=> '1.3',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory_uri() . '/lib/get-to-tekserve.zip'
		),
		
		array(
			'name' 		=> 'Google Analyticator',
			'slug' 		=> 'google-analyticator',
			'required' 	=> false,
			'version' 	=> '6.4.8',
			'force_activation' 	=> false,
		),
				
		array(
			'name' 		=> 'Gravity Forms',
			'slug' 		=> 'gravityforms',
			'required' 	=> true,
			'version' 	=> '1.8.17',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory_uri() . '/lib/gravityforms.zip'
		),
		
		array(
			'name' 		=> 'Pardot',
			'slug' 		=> 'pardot',
			'required' 	=> false,
			'version' 	=> '1.4',
			'force_activation' 	=> false,
		),
		
		array(
			'name' 		=> 'Revolution Slider',
			'slug' 		=> 'revslider',
			'required' 	=> true,
			'version' 	=> '4.1.4',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory_uri() . '/lib/revslider.zip'
		),
		
		array(
			'name' 		=> 'Scalable Vector Graphics (SVG)',
			'slug' 		=> 'scalable-vector-graphics-svg',
			'required' 	=> true,
			'version' 	=> '2.3.1',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'SlickMap CSS Sitemap',
			'slug' 		=> 'slickmap',
			'required' 	=> false,
			'version' 	=> '1.2.1',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory_uri() . '/lib/slickmap.zip'
		),
		
		array(
			'name' 		=> 'SortTable Post',
			'slug' 		=> 'sorttable-post',
			'required' 	=> true,
			'version' 	=> '4.2',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Tekserve Case Studies',
			'slug' 		=> 'tekserve-case-studies',
			'required' 	=> false,
			'version' 	=> '1.1.1',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory_uri() . '/lib/tekserve-case-studies.zip'
		),
		
		array(
			'name' 		=> 'Tekserve Google Analytics Events',
			'slug' 		=> 'tekserve-ga-events',
			'required' 	=> true,
			'version' 	=> '1.2',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory_uri() . '/lib/tekserve-ga-events.zip'
		),
		
		array(
			'name' 		=> 'Tekserve Press Mentions',
			'slug' 		=> 'tekserve-press-mentions',
			'required' 	=> false,
			'version' 	=> '1.2.1',
			'force_activation' 	=> false,
		),
		
		array(
			'name' 		=> 'Tekserve Shared Data',
			'slug' 		=> 'tekserve-shared-data',
			'required' 	=> true,
			'version' 	=> '1.2.1',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory_uri() . '/lib/tekserve-shared-data.zip'
		),
		
		array(
			'name' 		=> 'Tekserve Single Post Shortcode',
			'slug' 		=> 'tekserve-single-post-shortcode',
			'required' 	=> true,
			'version' 	=> '1.4.1',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory_uri() . '/lib/tekserve-single-post-shortcode.zip'
		),
		
		array(
			'name' 		=> 'Tekserve Testimonials',
			'slug' 		=> 'tekserve-testimonials',
			'required' 	=> false,
			'version' 	=> '1.1',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory_uri() . '/lib/tekserve-testimonials.zip'
		),
		
				
		array(
			'name' 		=> 'Tekserve Theme Widgets',
			'slug' 		=> 'tekserve_theme_widgets',
			'required' 	=> false,
			'version' 	=> '1.0',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory_uri() . '/lib/tekserve_theme_widgets.zip'
		),
		
		array(
			'name' 		=> 'Tekserve VCButtons',
			'slug' 		=> 'tekserve-vcbuttons',
			'required' 	=> true,
			'version' 	=> '1.2.3',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory_uri() . '/lib/tekserve-vcbuttons.zip'
		),
				
		array(
			'name' 		=> 'Use Google Libraries',
			'slug' 		=> 'use-google-libraries',
			'required' 	=> true,
			'version' 	=> '1.6.2',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'WordPress SEO by Yoast',
			'slug' 		=> 'wordpress-seo',
			'required' 	=> true,
			'version' 	=> '1.7.1',
			'force_activation' 	=> false,
		),
		
		array(
			'name' 		=> 'WPBakery Visual Composer',
			'slug' 		=> 'js_composer',
			'required' 	=> true,
			'version' 	=> '4.3.5',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory_uri() . '/lib/js_composer.zip'
		),
		
		array(
			'name' 		=> 'WYSIWYG Widgets',
			'slug' 		=> 'wysiwyg-widgets',
			'required' 	=> false,
			'version' 	=> '2.3.4',
			'force_activation' 	=> false,
		),

	);

	// Theme text domain, used for internationalising strings
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

// add_editor_style( 'style.css' );					//editor style tba p2; current default is effective for non-visual tinymce

/** Move labels to top of fields in comment form */
add_filter('genesis_comment_form_args','custom_email_note'); //
function custom_email_note() {
$args = array(

        'fields' => array(
            'author' =>  '<p class="comment-form-author">' .
            '<label for="author">' . __( 'Name', 'genesis' ) . '<span style="color:#f36f37"> *</span></label> ' .
	        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="1"' . $aria_req . ' />' .
	        ( $req ? '<span class="required">*</span>' : '' ) .
	        '</p>',

            'email' =>   '<p class="comment-form-email">' .
            '<label for="email">' . __( 'Email', 'genesis' ) . '<span style="color:#f36f37"> *</span></label> ' .
	        '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" tabindex="2"' . $aria_req . ' />' .
	        ( $req ? '<span class="required">*</span>' : '' ) .
	        '</p>',
         
            'url' => '<p class="comment-form-url">' .
            '<label for="url">' . __( 'Website', 'genesis' ) . '</label> ' .
	    	'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" tabindex="3" />' .
	    	'</p>'
        ),
                 
        'comment_field' =>   '<p class="comment-form-comment">' .
        '<label for="comment">' . __( 'Comment', 'genesis' ) . '</label> ' .
	    '<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" aria-required="true"></textarea>' .
	    '</p>',
                             
        'title_reply' => __( 'Comment', 'genesis' ),
        'label_submit' => __( 'Submit', 'genesis' ),
   );
    return $args;
}

/** Customize the credits */
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'tekserve_footer' );
function tekserve_footer() {
	echo '<div class="vc_row wpb_row vc_row-fluid">';
	echo '	<div class="vc_col-sm-1 wpb_column vc_column_container">';
	echo '		<a href="#wrap" rel="nofollow" title="Return to the top of the page" alt="Return to the top of the page"><i class="fa fa-2x fa-caret-square-o-up"></i></a>';
	echo '	</div>';
	echo '	<div class="vc_col-sm-3 wpb_column vc_column_container">';
	echo '		copyright &copy; ' . date('Y') . ' <a href="http://www.tekserve.com">Tekserve Corporation</a>';
	echo '	</div>';
	echo '	<div class="vc_col-sm-3 wpb_column vc_column_container">';
	echo '		<a href="http://www.tekserve.com/terms-of-use/">terms of use</a> - <a href="http://www.tekserve.com/privacy-policy/">privacy policy</a>';
	echo '	</div>';
	echo '	<div class="vc_col-sm-1 wpb_column vc_column_container">';
	echo '		<a href="http://www.tekserve.com/sitemap/">sitemap</a>';
	echo '	</div>';
	echo '	<div class="vc_col-sm-2 wpb_column vc_column_container">';
	echo '		<a href="http://nytm.org/made" target="_blank">diligently made in nyc</a>';
	echo '	</div>';
	echo '</div>';
	
}

/** All headers link to home page */
add_filter('genesis_seo_title', 'sp_seo_title', 10, 3);
function sp_seo_title($title, $inside, $wrap) {
	$inside = sprintf( '<a href="http://www.tekserve.com" title="%s">%s</a>', 'Go to Tekserve Homepage from this page - ' . esc_attr( get_bloginfo('name') ), get_bloginfo('name') );
	$title = sprintf('<%s id="title">%s</%s>', $wrap, $inside, $wrap);
	return $title;
}

/** Widget area for special notices sitewide */
// Register widget area
genesis_register_sidebar( array(
	'id'				=> 'below-header-banner',
	'name'			=> __( 'Between Header and Nav' ),
	'description'	=> __( 'A banner that shows up below the header; useful for sitewide notices.' ),
) );
// Add widget support for site. If widget not active, don't display
function tekserve_below_header_banner_genesis() {
		genesis_widget_area( 'below-header-banner', array(
			'before' => '<div id="special-notice"><div class="wrap">',
			'after' => '</div></div>',
		) );
 }
 // Place widget
add_action( 'genesis_after_header', 'tekserve_below_header_banner_genesis' );

/** Add post navigation (requires HTML5 support) */
add_action( 'genesis_after_entry_content', 'genesis_prev_next_post_nav', 5 );

//add editors to roles allowed to edit forms
function add_caps()
{
	$role = get_role( 'editor' );
	$role->add_cap( 'gform_full_access' );
}

add_action( 'admin_init', 'add_caps' );

// redirect rss aggregator link
add_filter( 'wprss_ftp_link_post_title', 'wprss_ftp_link_post_title_to_source' );
function wprss_ftp_link_post_title_to_source() { return TRUE; }

/****
	Footer Folk
****/

//add footer folk before footer
add_action( 'genesis_before_footer', 'footer_folk' );
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );


/**  Shortcode for footer folk  **/
function footer_folk() {
$zagat = get_stylesheet_directory_uri() . '/footer-images/logos/zagat.png';
$foursquare = get_stylesheet_directory_uri() . '/footer-images/logos/foursquare-logo.png';
$folk = get_stylesheet_directory_uri() . '/rotate.php';
if( get_post_type() == "page" ) {
	$html = '<div id="pageLoad"><img src="' . get_stylesheet_directory_uri() . '/images/ajax-loader.gif" alt="Page Loading" /></div>';
}
else {
	$html = null;
}
$html .= '
<div id="footer-folk" class="footer-folk" style="display:none;">
	<div class="footer-folk-wrap">
		<ul class="certificationlogos">
			<li id="bbblogo">
			</li>
			<li id="yelp-biz-badge-rrc-T-yDGKZZA71nkGQoPQCCng">
				Tekserve
			</li>
			<li id="zagatrating">
			</li>
			<li id="foursquare">
			</li>
		</ul>
	</div>
</div>

<script type="text/javascript">
	var zagaturl = "'.$zagat.'", foursquareurl = "'.$foursquare.'", folkurl = "'.$folk.'";
</script>
';
wp_enqueue_script ( 'footerfolk', get_stylesheet_directory_uri() . '/js/folk.js', array( 'jquery' ), '', true );
echo $html;
}
add_shortcode( 'footerfolk', 'footer_folk' );

//template-tag for footer-folk inside visual composer
function tekserve_footer_folk_vc() {
	$html = "<div class='wpb_row vc_row-fluid' style='margin-bottom: 0px !important; margin-bottom: 0px !important; border-bottom-width: 0px !important;'>
	<div class='vc_col-sm-12 wpb_column vc_column_container' style='min-height: 0px;'>
		<div class='wpb_wrapper' style='padding-bottom: 0px;'>".footer_folk()."</div></div></div>";
	echo $html;
}

/**  Visual Composer button  **/
if (function_exists('vc_map')) {
	vc_map( array(
	   "name" => __("Footer Folk"),
	   "base" => "footerfolk",
	   "class" => "",
	   "icon" => "icon-wpb-footerfolk",
	   "category" => __('Content'),
	   "admin_enqueue_css" => array(get_stylesheet_directory_uri().'/footer-folk.css')
	)	);
}

/****
	Tekbutton
****/

add_shortcode( 'tekbutton', 'tekbutton' );

function tekbutton( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'linkurl' => '#!'
	), $atts, 'tekbutton' );
	return '<a href="' . $a['linkurl'] . '"><span class="button">' . $content . '</span></a>';
}

/****
	Invisible Line
****/

add_shortcode( 'invisibleline', 'invisible_line' );

function invisible_line() {
	return '<hr style="visibility: hidden; clear: both; height: 1em;" /><br/>';
}

/****
	Map Trigger
****/

// add_shortcode( 'maptrigger', 'map_trigger' );
// 
// function map_trigger( $atts, $content = null ) {
// 	$a = shortcode_atts( array(
// 		'group' => 'triggers',
// 		'title' => $content
// 	), $atts, 'maptrigger' );
// 	return '<div class="collapseomatic colomat-visited drawertrigger" id="trigger-get-directions" rel="' . $a['group'] . '-highlander" title="' . $a['title'] . '" onclick="getToTekserveLoadScript();">' . $content . '</div>';
// }

/****
	Drawer Trigger
****/

// add_shortcode( 'drawertrigger', 'drawer_trigger' );
// 
// function drawer_trigger( $atts, $content = null ) {
// 	$a = shortcode_atts( array(
// 		'drawerid'	=> 'nodrawer',
// 		'title'		=> $content,
// 		'swaptitle'	=> '',
// 		'group'		=> 'triggers'
// 	), $atts, 'drawertrigger' );
// 	return '<div class="drawertrigger-container"><div class="collapseomatic colomat-visited drawertrigger scroll-to-trigger" id="extra1-' . $a['drawerid'] . '" rel="' . $a['group'] . '-highlander" title="' . $a['title'] . '" >' . $content . '</div><div id="swap-' . $a['drawerid'] . '" style="display:none;">' . $a['swaptitle'] . '</div></div>';
// }

/****
	Editor Buttons for Shortcodes
****/

add_action( 'init', 'apparition_tinymce_buttons' );

function apparition_tinymce_buttons() {
    add_filter( "mce_external_plugins", "add_apparition_tinymce_buttons" );
    add_filter( 'mce_buttons', 'register_apparition_tinymce_buttons' );
}

function add_apparition_tinymce_buttons( $plugin_array ) {
    $plugin_array['apparition'] = get_stylesheet_directory_uri() . '/tinymce/apparition-buttons.js';
    return $plugin_array;
}

function register_apparition_tinymce_buttons( $buttons ) {
    array_push( $buttons, 'tekbutton', 'invisibleline' ); 
    return $buttons;
}