<?php
/**
 * List View Template
 * The wrapper template for a list of events. This includes the Past Events and Upcoming Events views 
 * as well as those same views filtered to a specific category.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list.php
 *
 * @package TribeEventsCalendar
 * @since  2.1
 * @author Modern Tribe Inc.
 *
 */
 
if( ! defined( 'ABSPATH' ) ) {

	die('-1');

}	//end if( ! defined( 'ABSPATH' ) )

//check for vc_map function
if( function_exists( 'vc_map') ) {

	wp_enqueue_style( 'tribe_js_composer', plugins_url( '/js_composer/assets/css/js_composer.css' ) );

}	//end if( function_exists( 'vc_map') );
do_action( 'tribe_events_before_template' );
if( !( is_single() ) ) {

	?>
	<!-- List Header -->
    <?php do_action( 'tribe_events_before_header' ); ?>
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
	</div>
	<!-- #tribe-events-header -->
	<?php
	
}	//end if( !( is_single() ) )
?>
<!-- Tribe Bar -->
<?php tribe_get_template_part( 'modules/bar' ); ?>

<!-- Main Events Content -->
<?php tribe_get_template_part( 'list/content' ); ?>

<div class="tribe-clear"></div>

<?php do_action( 'tribe_events_after_template' ) ?>