<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Set Localization (do not remove) */
load_child_theme_textdomain( 'apparition', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'apparition' ) );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', __( 'Apparition Theme', 'apparition' ) );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/apparition' );

/** Add HTML5 support */
add_theme_support( ‘html5′ );

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

/** Move Subnav to page area before content container */
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_content', 'genesis_do_subnav' );

/** Genesis Single Post Navigation: Reverse link direction */
define( 'GSPN_REVERSE_LINK_DIRECTION', TRUE );

/** Add new image sizes */
add_image_size( 'featured-circle', 300, 300, TRUE );
add_image_size( 'featured-square', 300, 300, TRUE );

/** Unregister layout settings */
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );


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

/** Create search box in topnav */
add_filter( 'wp_nav_menu_items', 'genesis_search_primary_nav_menu', 10, 2 );

function genesis_search_primary_nav_menu( $menu, stdClass $args ){
        if ( 'primary' != $args->theme_location )
        	return $menu;
               if( genesis_get_option( 'nav_extras' ) )
                return $menu;
        if ( function_exists( 'tekserve_divert_relevanissi' ) ) {
			$menu .= sprintf( '<li class="tekserve_custom_search custom-search right search">%s</li>', __( tekserve_custom_search( $echo ) ) );
        }
        else {
        	$menu .= sprintf( '<li class="custom-search right search">%s</li>', __( genesis_search_form( $echo ) ) );
        }
        return $menu;
}


/** Include JS files that create full width sections and wraps with adaptive background colors */
function include_local_scripts() {
	wp_enqueue_script ( 'ui-elements', get_stylesheet_directory_uri() . '/js/ui-elements.js', array( 'jquery' ), '', true );
	wp_enqueue_script ( 'modernizr', 'http://modernizr.com/downloads/modernizr-latest.js', array( 'jquery' ), '', true );
 	wp_enqueue_script ( 'color2color', get_stylesheet_directory_uri() . '/js/color2color.js' );
 	wp_enqueue_script ( 'scroll-into-view', get_stylesheet_directory_uri() . '/js/jquery.scrollintoview.min.js', array( 'jquery' ), '', true );
 	wp_enqueue_style ( 'gspn', get_stylesheet_directory_uri() . '/gspn-additons.css' );
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
			'source'   				=> get_stylesheet_directory() . '/lib/plugins/tgm-example-plugin.zip', // The plugin source
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
			'version' 	=> '1.0.4',
			'force_activation' 	=> true,
		),

		array(
			'name' 		=> 'Advanced Custom Fields',
			'slug' 		=> 'advanced-custom-fields',
			'required' 	=> true,
			'version' 	=> '4.2.2',
			'force_activation' 	=> true,
		),
				
		array(
			'name' 		=> 'Collapse-Pro-Matic',
			'slug' 		=> 'collapse-pro-matic',
			'required' 	=> true,
			'version' 	=> '0.5',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory() . '/lib/collapse-pro-matic-v0.5.zip'
		),
		
		array(
			'name' 		=> 'Emma Emarketing Plugin for WordPress',
			'slug' 		=> 'emma-emarketing-plugin',
			'required' 	=> true,
			'version' 	=> '1.0.5',
			'force_activation' 	=> false,
		),
		
		array(
			'name' 		=> 'Font Awesome Icons',
			'slug' 		=> 'font-awesome',
			'required' 	=> true,
			'version' 	=> '3.2.1',
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
			'version' 	=> '2.0.0',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Genesis Simple Menus',
			'slug' 		=> 'genesis-simple-menus',
			'required' 	=> true,
			'version' 	=> '0.2',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Google Analyticator',
			'slug' 		=> 'google-analyticator',
			'required' 	=> true,
			'version' 	=> '6.4.5',
			'force_activation' 	=> false,
		),
				
		array(
			'name' 		=> 'Gravity Forms',
			'slug' 		=> 'gravityforms',
			'required' 	=> true,
			'version' 	=> '1.7.11',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory() . '/lib/gravityforms_1.7.12.zip'
		),
		
		array(
			'name' 		=> 'Relevanissi',
			'slug' 		=> 'relevanssi',
			'required' 	=> true,
			'version' 	=> '3.1.9',
			'force_activation' 	=> false,
		),
		
		array(
			'name' 		=> 'Revolution Slider',
			'slug' 		=> 'revslider',
			'required' 	=> true,
			'version' 	=> '3.0.95',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory() . '/lib/revslider.zip'
		),
		
		array(
			'name' 		=> 'Scalable Vector Graphics (SVG)',
			'slug' 		=> 'scalable-vector-graphics-svg',
			'required' 	=> true,
			'version' 	=> '2.1.1',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'SortTable Post',
			'slug' 		=> 'sorttable-post',
			'required' 	=> true,
			'version' 	=> '4.2',
			'force_activation' 	=> true,
		),
				
		array(
			'name' 		=> 'Tekserve Press Mentions',
			'slug' 		=> 'tekserve-press-mentions',
			'required' 	=> true,
			'version' 	=> '1.1',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Tekserve Single Post Shortcode',
			'slug' 		=> 'tekserve-single-post-shortcode',
			'required' 	=> true,
			'version' 	=> '1.1',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Tekserve Testimonials',
			'slug' 		=> 'tekserve-testimonials',
			'required' 	=> true,
			'version' 	=> '1.0',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Tekserve VCButtons',
			'slug' 		=> 'tekserve-vcbuttons',
			'required' 	=> true,
			'version' 	=> '1.0',
			'force_activation' 	=> true,
			'source'	=> get_stylesheet_directory() . '/lib/tekserve-vcbuttons.zip'
		),
				
		array(
			'name' 		=> 'Use Google Libraries',
			'slug' 		=> 'use-google-libraries',
			'required' 	=> true,
			'version' 	=> '1.5.2',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'Widgets on Pages',
			'slug' 		=> 'widgets-on-pages',
			'required' 	=> true,
			'version' 	=> '0.0.12',
			'force_activation' 	=> true,
		),
		
		array(
			'name' 		=> 'WPBakery Visual Composer',
			'slug' 		=> 'js_composer',
			'required' 	=> true,
			'version' 	=> '3.6.14.1',
			'force_activation' 	=> false,
			'source'	=> get_stylesheet_directory() . '/lib/js_composer.zip'
		),
		
		array(
			'name' 		=> 'WYSIWYG Widgets',
			'slug' 		=> 'wysiwyg-widgets',
			'required' 	=> true,
			'version' 	=> '2.3.1',
			'force_activation' 	=> true,
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

//magicNav has been deprecated.

//This (updater) is deprecated for launch, remains for data cleanup project
/** Register Custom Taxonomy for content update assignments */
if ( ! function_exists('updater') ) {
	function updater()  {
		$labels = array(
			'name'                       => 'Updaters',
			'singular_name'              => 'Updater',
			'menu_name'                  => 'Updater',
			'all_items'                  => 'All Updaters',
			'parent_item'                => 'Parent Updater',
			'parent_item_colon'          => 'Parent Updater:',
			'new_item_name'              => 'New Updater Name',
			'add_new_item'               => 'Add New Updater',
			'edit_item'                  => 'Edit Updater',
			'update_item'                => 'Update Updater',
			'separate_items_with_commas' => 'Separate Updaters with commas',
			'search_items'               => 'Search Updaters',
			'add_or_remove_items'        => 'Add or remove Updaters',
			'choose_from_most_used'      => 'Choose from existing Updaters',
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'query_var'                  => 'updater',
			'rewrite'                    => false,
		);
		register_taxonomy( 'updater', 'post', $args );
}
// Hook into the 'init' action
add_action( 'init', 'updater', 0 );
}

/** Customize the credits */
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'tekserve_footer' );
function tekserve_footer() {
	echo '<div class="gototop"><p><a href="#wrap" rel="nofollow">Return to top of page</a></p></div>';
	echo '<div class="creds">';
	echo '<div class="leftcopy">';
	echo '<a href="http://www.tekserve.com/terms-of-use/">Terms of Use</a> | ';
	echo '<a href="http://www.tekserve.com/privacy-policy/">Privacy Policy</a>';
	echo '</div>';
	echo '<div class="centercopy">';
	echo 'Copyright &copy; ';
	echo date('Y');
	echo ' <a href="http://www.tekserve.com">Tekserve Corporation</a>';
	echo '</div>';
	echo '<div class="rightcopy">';
	echo ' <a href="http://nytm.org/made">Dilligently Made in NYC</a>';
	echo '</div>';
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