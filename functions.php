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

// /** Unregister layout settings */
// genesis_unregister_layout( 'content-sidebar' );
// genesis_unregister_layout( 'sidebar-content' );
// genesis_unregister_layout( 'content-sidebar-sidebar' );
// genesis_unregister_layout( 'sidebar-content-sidebar' );
// genesis_unregister_layout( 'sidebar-sidebar-content' );
// 
// /** Unregister secondary sidebar */
// unregister_sidebar( 'sidebar' );
// unregister_sidebar( 'sidebar-alt' );

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

add_action( 'init', 'create_post_type_testimonial' );
function create_post_type_testimonial() {
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

add_action( 'init', 'create_post_type_press' );
function create_post_type_press() {
	register_post_type( 'press',
		array(
			'labels' => array(
				'name' => __( 'Press' ),
				'singular_name' => __( 'Press' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'press'),
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
// add_editor_style( 'style.css' );

add_filter('genesis_comment_form_args','custom_email_note'); //move labels to top of fields in comment form
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


add_filter( 'genesis_nav_items', 'magicNav', 10, 2 );
add_filter( 'wp_nav_menu_items', 'magicNav', 10, 2 );
function magicNav($menu, stdClass $args) {
	if ( is_page_template('single.php') ) {
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
		echo "<div id='categoriesTop'>";
		echo "<h1>Categories</h1>";
		echo "<ul id='categorylist'>";
		$categories = get_categories();
	foreach ($categories as $cat) {
		if ($cat->category_parent != 0) {
			echo '<span style="padding-left:10px;">';
		}
		echo '<a href="'.get_option('home').get_option('category_base').'/'.$cat->category_nicename.'/">'.$cat->cat_name.'</a> ('.$cat->category_count.')';
		if ($cat->category_description != '') {
			echo ' - '.$cat->category_description;
		}
		if ($cat->category_parent != 0) {
			echo '</span>';
		}
		echo '<br />';
		}
		echo "</ul>";
		echo "</div>";
		echo "</div>";
	// 	$menu .="<div id='magicNav'>{$popularButton}</div>";// .$popularButton.$facebookButton."</div>";
	// 	var_dump($popularButton);
	}
	return $menu;
}
//[repairstatus]
function repair_status_checker($atts){
return "<div id='status-title'><h2>Check Your Repair Status</h2></div><div id='status-wrapper'><div id='status-content'><p>Please use the form below to check the status of your repair at Tekserve. Just enter your SRO number (found in the upper right corner of your receipt) and billing zip code below.</p><img id='statusimg' src='' /><div id='fail-msg' style='display:none'><p style='padding:5px 0px'>The information you provided does not match what we have on record.<br />Please double check your information and try again. If it still isn't working for you, call us at: 212.929.3645</p><input onclick='javascript:document.location.reload()' class='button' type='button' value='Try Again'></input></div><div style='display:none' class='customer-info'><ul><li class='customer-info'><h3>Customer Info</h3><p></p></li><li id='product-info'><p></p></li></ul><li class='repair-details'><h3>Details</h3><ul class='repair-details'><li style='display: none'><p>During the first 1-3 business days, your repair will be processed and assigned to a technician.</p></li><li style='display: none'><p>A technician will work on your repair during this time. This will include confirming your issue, ordering replacement parts (if needed), and replacing the affected parts.</p></li><li style='display: none'><p>We are confirming that we resolved the issue.</p></li><li style='display: none'><p>The repair is done. It is ready to be picked up, if you have not made other arrangements.</p></li><li style='display: none'><p>Call Customer Support at 212.929.3645 for more information regarding this repair.</p></li></ul></li></div><form class='status-front' id='status-front' method='get'><p><span class='label'>SRO#</span> <a class='poplink' rel='facebox' href='whats_sro.php'>What's this?</a></p><p class='statusField'><input class='limit' name='sro1' id='sro1' type='text' value='' maxlength='1' size='1' tabindex='1' onkeyup='checkLen(this,this.value)'></input> - <input class='limit' name='sro2' id='sro2' type='text' value='' maxlength='3' size='3' tabindex='2' onkeyup='checkLen(this,this.value)'></input> - <input class='limit' name='sro3' id='sro3' type='text' value='' maxlength='3' size='3' tabindex='3' onkeyup='checkLen(this,this.value)'></input></p><p><span class='label'>Billing ZIP Code</span></p><p><input class='limited' name='zip' id='zip' type='text'  value='' maxlength='5' size='5' tabindex='4' onkeyup='checkLen(this,this.value)' /></p><div class='buttons'><button type='button' class='positive'>Submit</button></div></form></div></div></div><div></div>
<script type='text/javascript'>

var \$j = jQuery;
\$j('button.positive').click(function () {
    var img_base_path = 'http://www.tekserve.com/skin/frontend/tekserve/tekstore/';
    var repair_status = new Array('Created', 'In Progress', 'Testing', 'Complete', 'On Hold', 'Done', 'Service Complete');
    var sro1 = \$j('#sro1').val();
    var sro2 = \$j('#sro2').val();
    var sro3 = \$j('#sro3').val();
    var zip = \$j('#zip').val();
    var sro_zip = 'SRO#: ' + sro1 + '-' + sro2 + '-' + sro3 + '<br />' + 'Billing Zip Code: ' + zip;
    \$j.ajax({
        type: 'GET',
        dataType: 'jsonp',
        url: 'http://www.tekserve.com/status/?sro1=' + sro1 + '&sro2=' + sro2 + '&sro3=' + sro3 + '&zip=' + zip,
        success: function (msg) {
            \$j('#status-content').children('p').add('form.status-front').hide();
            if (msg == false) {
                \$j('#status-title').find('strong').html('Login Failed');
                \$j('#fail-msg').show();
                return;
            }
            var product_name = 'Product: ' + msg.product;
            \$j('#status-title').find('strong').html('Repair Status');
            \$j('form.status-front').hide();
            \$j('.customer-info').show();
            \$j('li.customer-info').children('p').html(sro_zip);
            \$j('#product-info').children('p').html(product_name);
            var result = msg.status;
            var result_index = \$j.inArray(result, repair_status);
            \$j('#status-content').children('img').attr('src', img_base_path + 'images/repair_stage_' + result_index + '.png');
            \$j('#status-content').children('img').show();
            var detail_cmt = \$j('ul.repair-details').find('li');
            detail_cmt[result_index].show();
        }
    });
});

function checkLen(x, y) {
    if (y.length == x.maxLength) {
        var next = x.tabIndex;
        if (next < document.getElementById('status-front').length) {
            document.getElementById('status-front').elements[next].focus();
        }
    }
}</script>";
}
add_shortcode( 'repairstatus', 'repair_status_checker' );

//custom shortcode for collapsomatic elements
function drawer( $atts, $content = null ) { // New function parameter $content is added!
   extract( shortcode_atts( array(
		'title' => 'Click Here',
		'swaptitle' => 'Click Here to Hide',
		'alt' => '',
		'notitle' => '',
		'id' => 'id'.$ran,
		'tag' => $options['tag'],
		'trigclass' => '',
		'trigpos' => $options['trigpos'],
		'targtag' => $options['targtag'],
		'targclass' => '',
		'targpos' => $options['targpos'],
		'rel' => '',
		'expanded' => '',
		'excerpt' => '',
		'excerptpos' => 'below-trigger',
		'excerpttag' => 'div',
		'excerptclass' => '',
		'swapexcerpt' => false,
		'findme' => '',
		'offset' => $options['offset'],
		'scrollonclose' => '',
		'startwrap' => '',
		'endwrap' => '',
		'elwraptag' => $options['wraptag'],
		'elwrapclass' => $options['wrapclass'],
		'cookiename' => '',
		'color' => 'none',
		'alignment' => 'left'
   ), $atts ) );
 
   $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content
 
   return "<div class='section ${color} dsection'><div class='drawer'><div id='${id}' class='collapseomatic colomat-hover ${alignment}' title='${title}'>${title}</div><div id='swap-${id}' style='display:none;'>${swaptitle}</div><div id='target-${id}' class='collapseomatic_content' style='display:none;'>${content}</div></div></div>";
// 
}
add_shortcode( 'drawer', 'drawer' );



//custom vc elements

vc_map( array(
   "name" => __("Repair Status Checker"),
   "base" => "repairstatus",
   "class" => "",
   "icon" => "icon-wpb-repairstatus",
   "category" => __('Content'),
   'admin_enqueue_css' => array('vc_extend/icons.css')
) );

vc_map( array(
   "name" => __("Drawer"),
   "base" => "drawer",
   "class" => "",
   "icon" => "icon-wpb-drawer",
   "category" => __('Content'),
   "admin_enqueue_css" => array('vc_extend/icons.css'),
   "params" => array(
	 array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Unique ID"),
         "param_name" => "id",
         "value" => __("click-here"),
         "description" => __("Required; Unique ID to identify drawer on this page. Use all lowercase, no special characters or spaces."),
         "admin_label" => true
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Title"),
         "param_name" => "title",
         "value" => __("Click Here"),
         "description" => __("Required; Text that user clicks on to expand drawer"),
         "admin_label" => true
      ),
      array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "",
         "heading" => __("Alignment"),
         "param_name" => "alignment",
         "value" => array("left", "leftcenter", "rightcenter", "right"),
         "description" => __("Required; Choose where the title text will appear on the page."),
         "admin_label" => true
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Alternate Title"),
         "param_name" => "swaptitle",
         "value" => __("Click Here to Hide"),
         "description" => __("Optional; Title that is displayed when drawer is open."),
         "admin_label" => true
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Unique ID"),
         "param_name" => "id",
         "value" => __("click-here"),
         "description" => __("Required; Unique ID to identify drawer on this page. Use all lowercase, no special characters or spaces."),
         "admin_label" => true
      ),
      array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "",
         "heading" => __("Background Color"),
         "param_name" => "color",
         "value" => array("white", "orange", "darkblue", "lightblue"),
         "description" => __("Choose the background color for this drawer."),
         "admin_label" => true
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => __("Content"),
         "param_name" => "content",
         "value" => __("<p>I am test text block. Click edit button to change this text.</p>"),
         "description" => __("Required; Enter the drop-down content of the drawer.")
   )
) 
	)
);

if ( ! function_exists('updater') ) {

// Register Custom Taxonomy for content update assignments
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


