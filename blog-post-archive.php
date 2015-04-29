<?php

/**
* Template Name: Apparition Post Archive
* Description: Infinite Scroll Blog page, headed by splash for latest article.
*/

function apparition_blog_archive_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter('excerpt_more', 'apparition_blog_archive_excerpt_more');

// Add our custom loop
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'apparition_post_loop' );

function apparition_post_loop() {

	if ( get_query_var('paged') ) {
		$paged = get_query_var('paged');
	}
	elseif ( get_query_var('page') ) {
		$paged = get_query_var('page');
	}
	else {
		$paged = 1;
	}

	$args = array(
		'orderby'       => 'post_date',
		'order'         => 'DESC',
		'posts_per_page'=> '12', // overrides posts per page in theme settings
		'paged' 		=> $paged,
	);

	$loop = new WP_Query( $args );
	if( $loop->have_posts() ) {
		
		echo '<link rel="stylesheet" id="js_composer_front-css" href="'.get_site_url().'/wp-content/plugins/js_composer/assets/css/js_composer.css?ver=4.5" type="text/css" media="all">';
		// loop through posts
		$i = 0;
		while( $loop->have_posts() ): $loop->the_post();
		$subtitle = get_post_meta( get_the_ID(), 'apparition_subtitle', true ) ? '<h2>' . get_post_meta( get_the_ID(), 'apparition_subtitle', true ) . '</h2>' : '';
		$cta = get_post_meta( get_the_ID(), 'apparition_cta', true ) ? '<h3>' . get_post_meta( get_the_ID(), 'apparition_cta', true ) . '</h3>' : '<h3>Read More</h3>';
		if( $i == 0 ) { ?>
			
		<div class="vc_row wpb_row vc_row-fluid hero">
			<div class="vc_col-sm-4 wpb_column vc_column_container">
				<a href="<?php the_permalink() ?>">
					<p class="heroimage"><?php the_post_thumbnail( 'large' ) ?></p>
				</a>
			</div>
			<div class="vc_col-sm-8 wpb_column vc_column_container">
				<a class="valigncenter" href="<?php the_permalink() ?>">
						<h1><?php the_title() ?></h1>
						<?php if( ! empty( $subtitle ) ) { echo $subtitle; }  ?>
						<?php echo $cta ?>
				</a>
			</div>
		</div>
		<div class="vc_row wpb_row vc_row-fluid hero-separator">
			<div class="vc_col-sm-12 wpb_column vc_column_container">
				<h1>RECENT POSTS</h1>
			</div>
		<?php
		}
		else {
			?> 
			<div class="vc_row wpb_row vc_row-fluid article-archive">
				<div class="vc_col-sm-3 wpb_column vc_column_container">
					<a href="<?php the_permalink() ?>">
						<?php the_post_thumbnail( 'medium' ) ?>
					</a>
				</div>
				<div class="vc_col-sm-9 wpb_column vc_column_container">
					<a href="<?php the_permalink() ?>">
						<h1><?php the_title() ?></h1>
						<p><?php the_excerpt() ?></p>
						<?php if( ! empty( $subtitle ) ) { echo $subtitle; }  ?>
						<?php echo $cta ?>
					</a>
				</div>
		<?php
		}
		?>
		</div>
		<?php
		$i++;
		endwhile;
		?>
		<div class="navigation">
			<div class="nav-previous alignleft">
				< <?php next_posts_link( 'Older posts', $loop->max_num_pages ) ?>
			</div>
			<div class="nav-next alignright">
				<?php previous_posts_link( 'Newer posts' ) ?> >
			</div>
		</div>
		<?php
		wp_reset_postdata();
	}
	
	else {
		?>
		<p>
			<?php _e('Sorry, no posts matched your criteria.'); ?>
		</p>
		<?php
	}


}


/**
 * Infinite Scroll
 */
function apparition_infinite_scroll_js() {
	?>
	<script>
	function blogHero() {
		var textheight = 0;
		var imgheight = jQuery('.hero .heroimage').height();
		jQuery('.hero .valigncenter > *').each( function() {
			textheight += jQuery(this).height();
		});
		if (textheight > imgheight) {
			jQuery('.hero .vc_column_container').css('maxHeight', textheight); 
		}
		else {
			jQuery('.hero .vc_column_container').css('maxHeight', imgheight);
		}
		if (jQuery('.hero .valigncenter').height() > imgheight) {
			jQuery('.hero .valigncenter').css('display', 'inline');
		}
		else {
			jQuery('.hero .valigncenter').css('display', 'block');
		}
		var textheight = 0;
		jQuery('.hero .valigncenter > *').each( function() {
			textheight += jQuery(this).height();
		});
	}
	var infinite_scroll = {
		loading: {
			img: "<?php echo get_stylesheet_directory_uri(); ?>/images/ajax-loader.gif",
			msgText: "<?php _e( 'Loading the next set of posts...', 'appartition' ); ?>",
			finishedMsg: "<?php _e( 'All posts loaded.', 'apparition' ); ?>"
		},
		"nextSelector":".navigation .nav-previous a",
		"navSelector":".navigation",
		"itemSelector":".article-archive",
		"contentSelector":"#content"
	};
	jQuery( infinite_scroll.contentSelector ).infinitescroll( infinite_scroll );
	</script>
	<?php
}
add_action( 'wp_footer', 'apparition_infinite_scroll_js',100 );

function apparition_infinite_enqueue() {
	wp_enqueue_script ( 'infinitejs', get_stylesheet_directory_uri() . '/js/jquery.infinitescroll.min.js', array( 'jquery', 'apparitionjs' ) );
	wp_enqueue_style ( 'newblog', get_stylesheet_directory_uri() . '/newblog.css' );
}

add_action( 'wp_enqueue_scripts', 'apparition_infinite_enqueue' );


genesis();