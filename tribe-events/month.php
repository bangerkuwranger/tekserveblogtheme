<?php
/**
 * Month View Template
 * The wrapper template for month view. 
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/month.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */
 

if( !defined('ABSPATH') ) {

	die('-1');

}	//end if( !defined('ABSPATH') )

//check for vc_map function
if( function_exists( 'vc_map' ) ) {

	wp_enqueue_style( 'tribe_js_composer', plugins_url( '/js_composer/assets/css/js_composer.css' ) );

}	//end if( function_exists( 'vc_map' ) )

do_action( 'tribe_events_before_template' ); ?>
<!-- Tribe Bar -->
<?php tribe_get_template_part( 'modules/bar' ); ?>

<!-- Main Events Content -->
<?php tribe_get_template_part('month/content'); ?>

<?php do_action( 'tribe_events_after_template' ) ?>
