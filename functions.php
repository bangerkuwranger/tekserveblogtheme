<?php
/****
	Start the engine
****/

require_once( get_template_directory() . '/lib/init.php' );



/****
	Set Localization (do not remove) 
****/

load_child_theme_textdomain( 'apparition', apply_filters( 'child_theme_textdomain', get_stylesheet_directory_uri() . '/languages', 'apparition' ) );



/****
	Child Theme Constants (do not remove)
****/

define( 'CHILD_THEME_NAME', __( 'Apparition Theme', 'apparition' ) );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/apparition' );
define( 'CHILD_THEME_LOC', get_stylesheet_directory_uri() );



/****
	Add HTML5 support
****/

add_theme_support( 'html5' );



/****
	Add Viewport meta tag for mobile browsers
****/

add_action( 'genesis_meta', 'apparition_add_viewport_meta_tag' );
function apparition_add_viewport_meta_tag() {

	echo '<meta name="viewport" content="initial-scale=1">';

}	//end apparition_add_viewport_meta_tag()



/****
	Add noscript tag
****/

add_action( 'genesis_before_header', 'apparition_noscript' );
function apparition_noscript() {
	?>
		<noscript>
			<style>#pageLoad { display: none; } #noscript-box { background: #fff; padding: .5em 1em 1em; margin-bottom: 1em; border-bottom: 4px solid #f36f37;  } .site-inner { display: block !important }</style>
			<div id="noscript-box">
				<h1>JavaScript Disabled</h1>
				<p>For full functionality of this page it is necessary to enable JavaScript. Here are the <a href="http://activatejavascript.org" target="_blank"> instructions how to enable JavaScript in your web browser</a></p>
				<p>If you need to contact us for any reason, we are available during regular business hours either by phone - 212.929.3645 - or in the store - 119 W. 23rd St. New York, NY 10011. We are also always available at <a href="mailto:info@tekserve.com">info@tekserve.com</a>.</p>
			</div>
		</noscript>
	<?php
		
}	//end apparition_noscript()


/****
	Force full width content layout - disabled to allow selection - uncomment to enable
****/

// add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );



/****
	Add support for custom background
****/

add_theme_support( 'custom-background' );



/****
	Create additional color style options
****/

add_theme_support( 'genesis-style-selector', array(

	'apparition-orange'		=>	__( 'Orange', 'apparition' ),
	'apparition-lightblue'		=>	__( 'Light Blue', 'apparition' ),
	'apparition-darkblue'		=>	__( 'Dark Blue', 'apparition' ),
	
	)

);



/****
	Add support for custom header
****/

add_theme_support( 'genesis-custom-header', array(
	
	'width'	=> 1140,
	'height'	=> 120

	)
);



/*****
	Set Content Width
****/

$content_width = 1140;



/****
	Remove the site description
****/

remove_action( 'genesis_site_description', 'genesis_seo_site_description' );



/****
	Add support for structural wraps
****/

add_theme_support( 'genesis-structural-wraps', array(

	'header',
	'nav',
	'inner',
	'footer-ets',
	'footer',
	'footer-widgets'

	)
);



/****
	Remove Subnav
****/
remove_action( 'genesis_after_header', 'genesis_do_subnav' );



/****
	Remove Titles from Pages
****/

add_action( 'get_header', 'apparition_remove_page_titles' );
function apparition_remove_page_titles() {

	if( get_post_type() == 'page' && !( is_search() ) ) {

		remove_action('genesis_entry_header', 'genesis_do_post_title');

	}	//end if( get_post_type() == 'page' && !( is_search() ) )

}	//end apparition_remove_page_titles()



/****
	Add Twitter Meta to single post pages
****/

add_action( 'wp_head','apparition_twitterMeta' );
function apparition_twitterMeta() {

	if( is_single() ) {

		echo '<meta name="twitter:widgets:csp" content="on">';

	}	//end if( is_single() )

}	//end apparition_twitterMeta()



/****
	Check for vc_map Function; Add js_composer css to Single Event Pages and Archives if it is Loaded
****/

add_action( 'wp_enqueue_scripts', 'jscomposerSinglePostFix' );
function jscomposerSinglePostFix() {

	if( function_exists( 'vc_map' ) && ( is_single() || is_archive() ) ) { 

		wp_enqueue_style( 'tribe_js_composer', plugins_url( '/js_composer/assets/css/js_composer.css' ) );

	}	//end if( function_exists( 'vc_map' ) && ( is_single() || is_archive() ) )
	
}	//end function jscomposerSinglePostFix()



/****
	Unregister Layout Settings
****/

genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );



/****
	Change 'more' Link Text - Different text for Archive and SRP
****/

add_filter( 'get_the_content_more_link', 'apparition_search_page_read_more_link' );
function apparition_search_page_read_more_link() {

	if ( is_search() ) {

		return '... <a class="more-link" href="' . get_permalink() . '"> See This Result</a>';
	
	}
	else {
	
		return '... <a class="more-link" href="' . get_permalink() . '"> Read More</a>';
	
	}	//end if ( is_search() )

}	//end apparition_search_page_read_more_link()



/****
	Add Odd/Even Post Class
****/

add_filter ( 'post_class' , 'apparition_oddeven_post_class' );
function apparition_oddeven_post_class( $classes ) {

	global $current_class;
	$classes[] = $current_class;
	$current_class = ($current_class == 'odd') ? 'even' : 'odd';
	return $classes;

}	//end apparition_oddeven_post_class( $classes )

//start with odd
global $current_class;
$current_class = 'odd';



/****
	Relocate the Genesis Post Image to Above Title
****/

remove_action( 'genesis_entry_content', 'genesis_do_post_format_image', 4);
// add_action( 'genesis_entry_header', 'genesis_do_post_image' );



/****
	Add the Featured Image Container; Use Placeholder Image if None Assigned
****/

add_action( 'genesis_entry_header', 'apparition_featured_image', 1 );
function apparition_featured_image() {

	if( is_singular( array( 'post' ) ) ) {
	
		$placeholderimg = '<img class="wp-post-image placeholder" alt="No Image Found for This Article" src="' . CHILD_THEME_LOC . '/images/blogplaceholder.jpg" />';
		echo '<div class="featured-image">';
		echo has_post_thumbnail() ?  get_the_post_thumbnail( $thumbnail->ID, 'large' ) : ($placeholderimg);
		echo '</div>';
	
	}	//end if( is_singular( array( 'post', 'page' ) ) )

}	//end apparition_featured_image()



/****
	Remove the Post Info
****/

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );




/****
	Custom Format for the Post Meta
****/

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'apparition_post_info', 12 );
function apparition_post_info() {

	if ( 'page' === get_post_type() ) {
	
		return;
	
	}
	echo '<p class="entry-meta"><time class="entry-time" itemprop="datePublished">';
	the_time( 'l, F j, Y' );
	echo '</time> by <span class="entry-author" itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">';
	the_author_link();
	echo '</span> ';
	edit_post_link();
	echo '</p>';

	

}	//end post_meta_filter( $post_meta )



/****
	Modify the Size of the Gravatar in the Author Box
****/

add_filter( 'genesis_author_box_gravatar_size', 'apparition_author_box_gravatar_size' );
function apparition_author_box_gravatar_size( $size ) {

	return '85';

}	//end apparition_author_box_gravatar_size( $size )



/****
	Customize the Comment Submit Button Text
****/

add_filter( 'comment_form_defaults', 'apparition_comment_form_args' );
function apparition_comment_form_args( $args ) {

	$args['label_submit'] = __( 'Submit', 'apparition' );
	return $args;

}	//end function apparition_comment_form_args( $args )




/****
	Set Footer to 5-column Layout
****/

add_theme_support( 'genesis-footer-widgets', 5 );



/****
	Include JS and CSS Files - NO STYLE RULES in '/style.css'
****/

add_action( 'wp_enqueue_scripts', 'apparition_include_local_scripts' );
function apparition_include_local_scripts() {

	wp_enqueue_style ( 'apparitioncss', CHILD_THEME_LOC . '/apparition.min.css' );
	wp_enqueue_script ( 'jquery-ui-core' );
	wp_enqueue_script ( 'apparitionjs', CHILD_THEME_LOC . '/js/apparition.min.js', array( 'jquery', 'jquery-ui-core' ) );
//
//	includes for separate files for debug
//
// 	wp_enqueue_script ( 'detailbox', CHILD_THEME_LOC . '/js/detailbox.js', array( 'jquery' ), '', true );
// 	wp_enqueue_script ( 'icaps', CHILD_THEME_LOC . '/js/icaps.js', array( 'jquery' ), '', true );
// 	wp_enqueue_script ( 'navmenu', CHILD_THEME_LOC . '/js/navmenu.js', array( 'jquery' ), '', true );
// 	wp_enqueue_script ( 'width', CHILD_THEME_LOC . '/js/width.js', array( 'jquery' ), '', true );
// 	wp_enqueue_script ( 'loadpage', CHILD_THEME_LOC . '/js/loadpage.js', array( 'jquery', 'detailbox', 'icaps', 'navmenu', 'width' ), '', true );
// 	wp_enqueue_script ( 'formtitles', CHILD_THEME_LOC . '/js/formtitles.js', array( 'jquery' ) );
// 	wp_enqueue_style ( 'newblogcss', CHILD_THEME_LOC . '/newblog.css' );
	
	
/**	Include User ID and Child Theme Directory in JS var: themeInfo	**/

	//get user id if logged in or set user id to 'guest' if not logged in
	$user_id = get_current_user_id();
	if ($user_id == 0) {
	
		$user_id = 'guest';
		
	}
	else {
	
		global $current_user;
    	get_currentuserinfo();
    	$user_id = $current_user->user_login;
    	
	}	//	end if ($user_id == 0)

	//include data as js variable
	$jsdata = array(
	
		'cssurl'	=> CHILD_THEME_LOC,
		'userid'	=> $user_id
	
	);
	wp_localize_script( 'apparitionjs', 'themeInfo', $jsdata );

}	//end apparition_include_local_scripts



/****
	Include TGMPA class to require include functional plugins - these functions are 3rd party or non-presentational
****/

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

/** This is an example of how to include a plugin pre-packaged with a theme

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

/** This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name' 		=> 'BuddyPress',
			'slug' 		=> 'buddypress',
			'required' 	=> false,
		), 
**/

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
			'source'	=> CHILD_THEME_LOC . '/lib/get-to-tekserve.zip'
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
			'required' 	=> false,
			'version' 	=> '1.9.8.3',
			'force_activation' 	=> false,
			'source'	=> CHILD_THEME_LOC . '/lib/gravityforms.zip'
		),
		
		array(
			'name' 		=> 'MailChimp List Subscribe Form',
			'slug' 		=> 'mailchimp',
			'required' 	=> false,
			'version' 	=> '1.4.2',
			'force_activation' 	=> false,
		),
		
		array(
			'name' 		=> 'Revolution Slider',
			'slug' 		=> 'revslider',
			'required' 	=> true,
			'version' 	=> '4.6.92',
			'force_activation' 	=> true,
			'source'	=> CHILD_THEME_LOC . '/lib/revslider.zip'
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
			'version' 	=> '1.3',
			'force_activation' 	=> false,
			'source'	=> CHILD_THEME_LOC . '/lib/slickmap.zip'
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
			'source'	=> CHILD_THEME_LOC . '/lib/tekserve-case-studies.zip'
		),
		
		array(
			'name' 		=> 'Tekserve Google Analytics Events',
			'slug' 		=> 'tekserve-ga-events',
			'required' 	=> true,
			'version' 	=> '1.2',
			'force_activation' 	=> false,
			'source'	=> CHILD_THEME_LOC . '/lib/tekserve-ga-events.zip'
		),
		
		array(
			'name' 		=> 'Tekserve Press Mentions',
			'slug' 		=> 'tekserve-press-mentions',
			'required' 	=> false,
			'version' 	=> '1.3',
			'force_activation' 	=> false,
			'source'	=> CHILD_THEME_LOC . '/lib/tekserve-press-mentions.zip'
		),
		
		array(
			'name' 		=> 'Tekserve Single Post Shortcode',
			'slug' 		=> 'tekserve-single-post-shortcode',
			'required' 	=> true,
			'version' 	=> '1.4.1',
			'force_activation' 	=> false,
			'source'	=> CHILD_THEME_LOC . '/lib/tekserve-single-post-shortcode.zip'
		),
		
		array(
			'name' 		=> 'Tekserve Testimonials',
			'slug' 		=> 'tekserve-testimonials',
			'required' 	=> false,
			'version' 	=> '1.2',
			'force_activation' 	=> false,
			'source'	=> CHILD_THEME_LOC . '/lib/tekserve-testimonials.zip'
		),
		
				
		array(
			'name' 		=> 'Tekserve Theme Widgets',
			'slug' 		=> 'tekserve_theme_widgets',
			'required' 	=> false,
			'version' 	=> '1.1',
			'force_activation' 	=> false,
			'source'	=> CHILD_THEME_LOC . '/lib/tekserve_theme_widgets.zip'
		),
		
		array(
			'name' 		=> 'Tekserve VCButtons',
			'slug' 		=> 'tekserve-vcbuttons',
			'required' 	=> true,
			'version' 	=> '1.2.3',
			'force_activation' 	=> true,
			'source'	=> CHILD_THEME_LOC . '/lib/tekserve-vcbuttons.zip'
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
			'version' 	=> '2.1.1',
			'force_activation' 	=> false,
		),
		
		array(
			'name' 		=> 'WPBakery Visual Composer',
			'slug' 		=> 'js_composer',
			'required' 	=> true,
			'version' 	=> '4.5',
			'force_activation' 	=> false,
			'source'	=> CHILD_THEME_LOC . '/lib/js_composer.zip'
		),
		
		array(
			'name' 		=> 'WYSIWYG Widgets',
			'slug' 		=> 'wysiwyg-widgets',
			'required' 	=> false,
			'version' 	=> '2.3.5',
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
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
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

}	//end function apparition_tekserve_register_required_plugins()



// add_editor_style( 'style.css' );					//editor style tba p2; current default is effective for non-visual tinymce



/****
	Customize Fields in Comment Form
****/

add_filter( 'comment_form_defaults', 'apparition_respond_form' );
function apparition_respond_form( $args ) {

	$args['fields'] = array( 
	
		'author' => '<p class="comment-form-author comment-field required"><label for="author">Name</label><input id="author" name="author" type="text" value="" size="30" tabindex="1" aria-required="true" />',
		'email' => '<p class="comment-form-email comment-field required"><label for="email">Email</label><input id="email" name="email" type="text" value="" size="30" tabindex="2" aria-required="true" />',
		
		);
	$args['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . __( 'Comment', 'apparition' ) . '</label><textarea id="comment" name="comment" cols="45" rows="4" tabindex="4" aria-required="true" placeholder="enter your comment here"></textarea></p>';
	$args['title_reply'] = __( 'Comment', 'apparition' );
	$args['label_submit'] = __( 'Submit', 'apparition' );
	$args['comment_notes_before'] = '';
	return $args;
    
}	//end apparition_respond_form( $args )



/****
	Customize the Footer Credits
****/

remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'apparition_tekserve_footer' );
function apparition_tekserve_footer() {

	?>
	<div class="vc_row wpb_row vc_row-fluid">
		<div class="vc_col-sm-1 wpb_column vc_column_container">
			<a href="#wrap" rel="nofollow" title="Return to the top of the page" alt="Return to the top of the page"><i class="fa fa-2x fa-caret-square-o-up"></i></a>
		</div>
		<div class="vc_col-sm-3 wpb_column vc_column_container">
			copyright &copy;<?php echo date('Y') ?>&nbsp;<a href="http://www.tekserve.com">Tekserve Corporation</a>
		</div>
		<div class="vc_col-sm-3 wpb_column vc_column_container">
			<a href="http://www.tekserve.com/terms-of-use/">terms of use</a> - <a href="http://www.tekserve.com/privacy-policy/">privacy policy</a>
		</div>
		<div class="vc_col-sm-1 wpb_column vc_column_container">
			<a href="http://www.tekserve.com/sitemap/">sitemap</a>
		</div>
		<div class="vc_col-sm-2 wpb_column vc_column_container">
			<a href="http://nytm.org/made" target="_blank">diligently made in nyc</a>
		</div>
	</div>
	<?php
	
}	//end function apparition_tekserve_footer()



/****
	All headers link to WWW
****/

add_filter( 'genesis_seo_title', 'apparition_tekserve_seo_title', 10, 3 );
function apparition_tekserve_seo_title( $title, $inside, $wrap ) {

	$inside = sprintf( '<a href="http://www.tekserve.com" title="%s">%s</a>', 'Go to Tekserve Homepage from this page - ' . esc_attr( get_bloginfo('name') ), get_bloginfo('name') );
	$title = sprintf( '<%s id="title">%s</%s>', $wrap, $inside, $wrap );
	return $title;

}	//end apparition_tekserve_seo_title( $title, $inside, $wrap )



/****
	Create Widget Area below Header for Special Notices
****/

genesis_register_sidebar( array(

	'id'				=> 'below-header-banner',
	'name'			=> __( 'Below Header' ),
	'description'	=> __( 'A banner that shows up below the header; useful for sitewide notices.' ),

	)

);
// Add widget support for site. If widget not active, don't display
add_action( 'genesis_after_header', 'apparition_below_header_banner' );
function apparition_below_header_banner() {
	genesis_widget_area( 'below-header-banner', array(
	
		'before' => '<div id="special-notice"><div class="wrap">',
		'after' => '</div></div>',
		
		) 
	);

}	//end apparition_below_header_banner()



/****
	Add Post Navigation (requires HTML5 support, so not active until that is)
****/

// add_action( 'genesis_after_entry_content', 'genesis_prev_next_post_nav', 5 );



/****
	Function to Get Auto Post Excerpt outside of Loop
****/

function get_excerpt_by_id( $post_id ) {

    $the_post = get_post( $post_id ); //Gets post ID
    $the_excerpt = $the_post->post_excerpt;
    if( empty( $the_excerpt ) ) {
    
		$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
		$excerpt_length = 50; //Sets excerpt length by word count
		$the_excerpt = strip_tags( strip_shortcodes( $the_excerpt ) ); //Strips tags and images
		$words = explode( ' ', $the_excerpt, $excerpt_length + 1 );

		if( count( $words ) > $excerpt_length ) {
	
			array_pop( $words );
			array_push( $words, '…' );
			$the_excerpt = implode( ' ', $words );
   
	   }	//end if( count( $words ) > $excerpt_length )
   
   }	//end if( empty( $the_excerpt ) )

    return $the_excerpt;

}	//end get_excerpt_by_id( $post_id )



/****
	Rich Post Navigation - Use to Replace Default Single Post Nav with Preview of Next/Previous Post - Uncomment Line to Enable
****/

// add_action( 'genesis_entry_footer', 'appartition_rich_post_nav' );
function appartition_rich_post_nav() {

	$prev_post = get_previous_post( false, '1651' );
	$next_post = get_next_post( false, '1651' );
	$post_nav = '';
	if( ! empty( $prev_post ) || ! empty( $next_post ) ) {

		$post_nav .= '<div class="rich-post-nav">';
		$post_nav .= '<div class="rich-post-nav-separator vc_row wpb_row vc_row-fluid"><div class="vc_col-sm-12 wpb_column vc_column_container"><h1>Keep Reading</h1></div></div>';
		$post_nav .= '<div class="rich-post-nav-articles">';
		if( ! empty( $prev_post ) ) {
	
			$post_nav .= '<div class="vc_row wpb_row vc_row-fluid article-archive">
				<div class="vc_col-sm-3 wpb_column vc_column_container">
					<a class="rich-post-nav-link" href="' . get_permalink( $prev_post->ID ) . '" name="' . sanitize_title( $prev_post->post_title ) . '-image">';
			$placeholderimg = '<img class="wp-post-image placeholder" alt="No Image Found for This Article" src="' . CHILD_THEME_LOC . '/images/blogplaceholder.jpg" />';
			$featured = has_post_thumbnail( $prev_post->ID ) ?  get_the_post_thumbnail( $prev_post->ID, 'medium' ) : $placeholderimg;
			$post_nav .=  $featured . '
					</a>
				</div>';
			$post_nav .= '
				<div class="vc_col-sm-9 wpb_column vc_column_container"> 
					<a class="rich-post-nav-link" href="' . get_permalink( $prev_post->ID ) . '" name="' . sanitize_title( $prev_post->post_title ) . '-content">';
			$cta = $prev_post->apparition_post_cta ? '<h3>' . $prev_post->apparition_post_cta . '</h3>' : '<h3>Read More</h3>';
			$post_nav .= '	<h1>' . $prev_post->post_title . '</h1>
							<p>' . get_excerpt_by_id( $prev_post->ID ) . '</p>
							' . $cta ;
			$post_nav .= '
					</a>
				</div>
			</div>';
		
		}	//end if( ! empty( $prev_post ) )
		if( ! empty( $next_post ) ) {
		
			$post_nav .= '<div class="vc_row wpb_row vc_row-fluid article-archive">
				<div class="vc_col-sm-3 wpb_column vc_column_container">
					<a class="rich-post-nav-link" href="' . get_permalink( $next_post->ID ) . '" name="' . sanitize_title( $next_post->post_title ) . '-image">';
			$placeholderimg = '<img class="wp-post-image placeholder" alt="No Image Found for This Article" src="' . CHILD_THEME_LOC . '/images/blogplaceholder.jpg" />';
			$featured = has_post_thumbnail( $next_post->ID ) ?  get_the_post_thumbnail( $next_post->ID, 'medium' ) : $placeholderimg;
			$post_nav .=  $featured . '
					</a>
				</div>';
			$post_nav .= '
				<div class="vc_col-sm-9 wpb_column vc_column_container"> 
					<a class="rich-post-nav-link" href="' . get_permalink( $next_post->ID ) . '" name="' . sanitize_title( $next_post->post_title ) . '-content">';
			$cta = $next_post->apparition_post_cta ? '<h3>' . $next_post->apparition_post_cta . '</h3>' : '<h3>Read More</h3>';
			$post_nav .= '	<h1>' . $next_post->post_title . '</h1>
							<p>' . get_excerpt_by_id( $next_post->ID ) . '</p>
							' . $cta ;
			$post_nav .= '
					</a>
				</div>
			</div>';
		
		}	//if( ! empty( $next_post ) )
		$post_nav .= '</div></div>';
		echo $post_nav;
	
	}	//end if( ! empty( $prev_post ) || ! empty( $next_post ) )
	
}	//end appartition_rich_post_nav




/****
	Add Custom Default Avatar (static)
****/

add_filter( 'avatar_defaults', 'apparition_default_avatar' );
function apparition_default_avatar( $avatar_defaults ) {

    $myavatar = CHILD_THEME_LOC . '/images/apparition-avatar.jpg';
    $avatar_defaults[$myavatar] = "Tekserve";
    return $avatar_defaults;

}	//end apparition_default_avatar( $avatar_defaults )



/****
	Change Comments/Comment Form Output
****/

//Change Title Text of Comment Form
add_filter( 'comment_form_defaults', 'apparition_comment_form_defaults' );
function apparition_comment_form_defaults( $defaults ) {
 
	$defaults['title_reply'] = __( 'Comments' );
	$defaults['comment_notes_after'] = '';
	return $defaults;
 
}	//end apparition_comment_form_defaults( $defaults )

//Remove Title Text from Comments
add_filter( 'genesis_title_comments', 'apparition_genesis_title_comments' );
function apparition_genesis_title_comments() {

	$title = '';
	return $title;

}	//end function apparition_genesis_title_comments()

//Move Comment Form to before Comments, if Post Has Comments
add_action( 'genesis_before_comments', 'apparition_move_comments_form' );
function apparition_move_comments_form() {

	global $wp_query;
	if ( have_comments() ) {
	
		remove_action( 'genesis_comment_form', 'genesis_do_comment_form' );
		add_action( 'genesis_comments', 'genesis_do_comment_form', 5 );
	
	}	//end if ( have_comments() )

}	//end apparition_move_comments_form()

//Use Custom Display Loop for Comments
remove_action( 'genesis_list_comments', 'genesis_default_list_comments' );
add_action( 'genesis_list_comments', 'apparition_default_list_comments' );
function apparition_default_list_comments() {

	$args = array(
        'type'          => 'comment',
        'avatar_size'   => 43,
        'callback'      => 'apparition_comment_callback',
    );
 
    $args = apply_filters( 'genesis_comment_list_args', $args );
 
    wp_list_comments( $args );

}	//end apparition_default_list_comments()

//Custom Display Loop for Comments
function apparition_comment_callback( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;	?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

		<?php do_action( 'genesis_before_comment' ); ?>
		
		<div class="comment-author-avatar">
			<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</div>
		<div class="comment-content">
			<div class="comment-author vcard">
				<?php printf( __( '<cite class="fn">%s</cite> <span class="says">%s</span>', 'genesis' ), get_comment_author_link(), apply_filters( 'comment_author_says_text', __( '', 'genesis' ) ) ); ?>
		 	</div>

			<div class="comment-meta commentmetadata">
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '<span class="says">wrote on </span>%1$s at %2$s', 'genesis' ), get_comment_date(), get_comment_time() ); ?></a>
				<?php edit_comment_link( __( '(Edit)', 'genesis' ), '' ); ?>
			</div>

		
			<?php if( !( $comment->comment_approved ) ) : ?>
				<p class="alert"><?php echo apply_filters( 'genesis_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'genesis' ) ); ?></p>
			<?php endif; ?>

			<?php comment_text(); ?>
		</div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>

		<?php do_action( 'genesis_after_comment' );

	//* No ending </li> tag because of comment threading

}	//end apparition_comment_callback( $comment, $args, $depth )



/****
	Allow Editors to Edit Gravity Forms
****/

add_action( 'admin_init', 'apparition_add_gform_caps' );
function apparition_add_gform_caps() {

	$role = get_role( 'editor' );
	$role->add_cap( 'gform_full_access' );

}	//end apparition_add_gform_caps()



/****
	Redirect RSS Aggregator Links
****/

add_filter( 'wprss_ftp_link_post_title', 'apparition_wprss_ftp_link_post_title_to_source' );
function apparition_wprss_ftp_link_post_title_to_source() { 

	return TRUE;

}	//end apparition_wprss_ftp_link_post_title_to_source()



/****
	Add 'Footer Folk' Section - N.B. Section was named for now removed branding elements: randomly rotating collection of staff images accompanied by ratings. This is now just the ratings.
****/

//add footer folk before footer and force footer widgets to load below
add_action( 'genesis_before_footer', 'apparition_footer_folk' );
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

/**  Shortcode for footer folk  **/
add_shortcode( 'footerfolk', 'apparition_footer_folk' );
function apparition_footer_folk() {

	$zagat = CHILD_THEME_LOC . '/images/footer-images/logos/zagat.png';
	$foursquare = CHILD_THEME_LOC . '/images/footer-images/logos/foursquare-logo.png';
	if( get_post_type() == 'page' ): ?>
		<div id="pageLoad"><img src="<?php echo CHILD_THEME_LOC ?>/images/ajax-loader.gif" alt="Page Loading" /></div>
		
	<?php endif ?>
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
		themeInfo.zagaturl = "<?php echo $zagat ?>", themeInfo.foursquareurl = "<?php echo $foursquare ?>";
	</script>
	<?php
	//loads content after page is complete - some 3rd Party generated content can lock the page otherwise
	wp_enqueue_script ( 'footerfolk', CHILD_THEME_LOC . '/js/folk.min.js', array( 'jquery' ), '', true );

}	//end apparition_footer_folk()

//template-tag for footer-folk inside visual composer
function apparition_footer_folk_vc() {

	if( function_exists( 'vc_map' ) ):
	?>
	
	<div class="wpb_row vc_row-fluid" style="margin-bottom: 0px !important; margin-bottom: 0px !important; border-bottom-width: 0px !important;">
		<div class="vc_col-sm-12 wpb_column vc_column_container" style="min-height: 0px;">
			<div class="wpb_wrapper" style="padding-bottom: 0px;">
				<?php apparition_footer_folk() ?>
			</div>
		</div>
	</div>
	
	<?php
	endif;

}	//end apparition_footer_folk_vc()



/****
	Custom Post Fields
****/

//create custom fields for post details
add_action( 'admin_init', 'apparition_custom_post_fields' );
function apparition_custom_post_fields() {

    add_meta_box( 'apparition_custom_post_fields', 'Blog Post Fields', 'apparition_custom_post_fields_metabox', 'post', 'normal', 'high' );

}	//end apparition_custom_post_fields()



// Retrieve current post details based on ID
function apparition_custom_post_fields_metabox( $post ) {

	wp_nonce_field( 'apparition_custom_post_fields', 'apparition_custom_post_fields_nonce' );
	$apparition_post_subtitle = esc_html( get_post_meta( $post->ID, 'apparition_post_subtitle', true ) );
	$apparition_post_cta = esc_html( get_post_meta( $post->ID, 'apparition_post_cta', true ) );
	?>
    <table>
        <tr>
            <td style="width: 100%">Subtitle (leave blank for none)</td>
        </tr>
        <tr>
            <td><input type="text" size="130" name="apparition_post_subtitle" value="<?php echo $apparition_post_subtitle; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">CTA Text (leave blank for default: 'READ MORE')</td>
        </tr>
        <tr>
            <td><input type="text" size="130" name="apparition_post_cta" value="<?php echo $apparition_post_cta; ?>" /></td>
        </tr>
    </table>
    <?php

}	//end apparition_custom_post_fields_metabox( $post )



//store custom post field data on post save
add_action( 'save_post', 'save_apparition_custom_post_fields', 5, 2 );
function save_apparition_custom_post_fields( $post_id, $post ) {

	// SecCheck
	if( ! isset( $_POST['apparition_custom_post_fields_nonce'] ) ) {
	
		return $post_id;
	
	}	//end if( ! isset( $_POST['apparition_custom_post_fields_nonce'] ) )
	
	$nonce = $_POST['apparition_custom_post_fields_nonce'];

	// Verify that the nonce is valid.
	if( ! wp_verify_nonce( $nonce, 'apparition_custom_post_fields' ) ) {
	
		return $post_id;
	
	}	//end if( ! wp_verify_nonce( $nonce, 'apparition_custom_post_fields' ) ) {
	
	if( ! current_user_can( 'edit_post', $post_id ) ) {
	
		return $post_id;
	
	}	//end if( ! current_user_can( 'edit_post', $post_id ) )
	
    // Check post type for 'post'
    if( $post->post_type == 'post' ) {
    
        // Store data in post meta table if present in post data
        if( isset( $_POST['apparition_post_subtitle'] ) ) {
        
            update_post_meta( $post_id, 'apparition_post_subtitle', sanitize_text_field( $_REQUEST['apparition_post_subtitle'] ) );
    	
    	}	//end if( isset( $_POST['apparition_post_subtitle'] ) )
    	if( isset( $_POST['apparition_post_cta'] ) ) {
    	
            update_post_meta( $post_id, 'apparition_post_cta', sanitize_text_field( $_REQUEST['apparition_post_cta'] ) );
    	
    	}	//end if( isset( $_POST['apparition_post_cta'] ) )
    
    }
    else {
    
    	return $post_id;
    
    }	//end if( $post->post_type == 'post' )

}	//end save_apparition_custom_post_fields( $post_id, $post )



// Visual Composer Button for Footer Folk
if( function_exists( 'vc_map' ) ) {

	vc_map( array(
	
	   "name" => __("Footer Folk"),
	   "base" => "footerfolk",
	   "class" => "",
	   "icon" => "icon-wpb-footerfolk",
	   "category" => __('Content'),
	   "admin_enqueue_css" => array( CHILD_THEME_LOC.'/footer-folk.css' )
		
		)
	);

}	//end if( function_exists( 'vc_map' ) )



/****
	Create Tekbutton Shortcode
****/

add_shortcode( 'tekbutton', 'apparition_tekbutton' );
function apparition_tekbutton( $atts, $content = null ) {

	$a = shortcode_atts( array(
	
		'linkurl' => '#!'
	
	), $atts, 'tekbutton' );
	return '<a href="' . $a['linkurl'] . '"><span class="button">' . $content . '</span></a>';

}	//end apparition_tekbutton( $atts, $content = null )



/****
	Create Invisible Line Shortcode
****/

add_shortcode( 'invisibleline', 'apparition_invisible_line' );
function apparition_invisible_line() {

	return '<hr style="visibility: hidden; clear: both; height: 1em;" />';

}	//end apparition_invisible_line()



/****
	Create Map Trigger Shortcode (uses collapseomatic/collapsepromatic plugin) - Uncomment line to enable, Uncomment code in tMCE buttons section in this file and related JS (/tinymce/apparition-buttons.js) to enable editor buttons
****/

// add_shortcode( 'maptrigger', 'apparition_map_trigger' );
function apparition_map_trigger( $atts, $content = null ) {

	$a = shortcode_atts( array(
	
		'group' => 'triggers',
		'title' => $content
	
	), $atts, 'maptrigger' );
	return '<div class="collapseomatic colomat-visited drawertrigger" id="trigger-get-directions" rel="' . $a['group'] . '-highlander" title="' . $a['title'] . '" onclick="getToTekserveLoadScript();">' . $content . '</div>';

}	//end apparition_map_trigger( $atts, $content = null )

/****
	Create Drawer Trigger Shortcode (uses collapseomatic/collapsepromatic plugin) - Uncomment line to enable, Uncomment code in tMCE buttons section in this file and related JS (/tinymce/apparition-buttons.js) to enable editor buttons
****/

// add_shortcode( 'drawertrigger', 'apparition_drawer_trigger' );
function apparition_drawer_trigger( $atts, $content = null ) {

	$a = shortcode_atts( array(
	
		'drawerid'	=> 'nodrawer',
		'title'		=> $content,
		'swaptitle'	=> '',
		'group'		=> 'triggers'
	
	), $atts, 'drawertrigger' );
	return '<div class="drawertrigger-container"><div class="collapseomatic colomat-visited drawertrigger scroll-to-trigger" id="extra1-' . $a['drawerid'] . '" rel="' . $a['group'] . '-highlander" title="' . $a['title'] . '" >' . $content . '</div><div id="swap-' . $a['drawerid'] . '" style="display:none;">' . $a['swaptitle'] . '</div></div>';

}	//end apparition_drawer_trigger( $atts, $content = null )



/****
	Add Editor Buttons for Shortcodes: Tekbutton, Invisible Line
****/

//use filters to add tMCE Plugin and Buttons
add_action( 'init', 'apparition_tinymce_buttons' );
function apparition_tinymce_buttons() {

    add_filter( "mce_external_plugins", "apparition_add_tinymce_plugin" );
    add_filter( 'mce_buttons', 'apparition_register_tinymce_buttons' );

}	//end apparition_tinymce_buttons()
//add apparition plugin to tMCE
function apparition_add_tinymce_plugin( $plugin_array ) {

    $plugin_array['apparition'] = CHILD_THEME_LOC . '/tinymce/apparition-buttons.js';
    return $plugin_array;

}	//end apparition_add_tinymce_plugin( $plugin_array )
//register shortcode buttons with tMCE
function apparition_register_tinymce_buttons( $buttons ) {

    array_push( $buttons, 'tekbutton', 'invisibleline' );
//     array_push( $buttons, 'maptrigger', 'drawertrigger' );
    return $buttons;

}	//end apparition_register_tinymce_buttons( $buttons )