<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 * 
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  2.1
 * @author Modern Tribe Inc.
 *
 */
 
genesis_do_subnav();

if ( !defined('ABSPATH') ) { die('-1'); }

$event_id = get_the_ID();

$datedetails = tribe_events_event_schedule_details();
$datedetails = str_replace('@', 'From', $datedetails);
$datedetails = str_replace('-', 'To', $datedetails);
$organizer = tribe_get_organizer();
if ( strpos( $organizer, 'Tekserve' ) !== false ) {
	$organizer = '';
}
else {
	$organizer = '<div><h3 style="margin-bottom: 1em;">Presented by: '.$organizer.'</h3></div>';
}
$event_link = tribe_get_event_website_url();
if ( !empty( $event_link ) ) {
	$event_link = '<div><h3 style="margin-bottom: 1em;">Visit the <a href="'.$event_link.'">Event Page</a> provided by the presenter.</h3></div>';
}

?>

<div id="tribe-events-content" class="tribe-events-single">

	<p class="tribe-events-back"><a href="<?php echo tribe_get_events_link() ?>"> <?php _e( '&laquo; All Events', 'tribe-events-calendar' ) ?></a></p>

	<!-- Notices -->
	<?php tribe_events_the_notices() ?>

	<?php the_title( '<h2 class="tribe-events-single-event-title summary">', '</h2>' ); ?>

	<div class="tribe-events-schedule updated published tribe-clearfix">
		<h3><?php echo $datedetails ?></h3>
		<!-- Event meta -->
		<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
		<h3 style="display: block; margin-bottom: 1em;">At <?php echo tribe_get_venue_link();
		echo tribe_get_full_address(); ?>
		<?php  if ( tribe_get_cost() ) :  ?>
			<span class="tribe-events-divider">|</span>
			<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
		<?php endif; ?>
		</h3>
		<?php echo $organizer; ?>
		<?php echo $event_link ?>
		<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
		<?php echo tribe_events_event_recurring_info_tooltip(); ?>
		
	</div>

	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php _e( 'Event Navigation', 'tribe-events-calendar' ) ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '&laquo; %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% &raquo;' ) ?></li>
		</ul><!-- .tribe-events-sub-nav -->
	</div><!-- #tribe-events-header -->

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('vevent'); ?>>
			<!-- Event featured image -->


			<?php echo tribe_event_featured_image(); ?>

			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content entry-content description">
				<h2>About This Event</h2>
				<?php the_content(); ?>
			</div><!-- .tribe-events-single-event-description -->
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>

			
			</div><!-- .hentry .vevent -->
		<?php /*  Changed out comments_template() function (native WP) in favor of Genesis version, genesis_get_comments_template()  */
		if( get_post_type() == TribeEvents::POSTTYPE && tribe_get_option( 'showComments','no' ) == 'yes' ) { genesis_get_comments_template(); } ?>

	<?php endwhile; ?>

	<!-- Event footer -->
    <div id="tribe-events-footer">
		<!-- Navigation -->
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php _e( 'Event Navigation', 'tribe-events-calendar' ) ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '&laquo; %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% &raquo;' ) ?></li>
		</ul><!-- .tribe-events-sub-nav -->
	</div><!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->
