<?php

/**
* Template Name: Apparition Post Archive
* Description: Infinite Scroll Blog page, headed by splash for latest article.
*/

// Add our custom loop
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'apparition_post_loop' );

function apparition_post_loop() {

	$args = array(
// 		'category_name' => 'genesis-office-hours', // replace with your category slug
		'orderby'       => 'post_date',
		'order'         => 'DESC',
// 		'posts_per_page'=> '12', // overrides posts per page in theme settings
	);

	$loop = new WP_Query( $args );
	if( $loop->have_posts() ) {
		
		echo '<link rel="stylesheet" id="js_composer_front-css" href="'.get_site_url().'/wp-content/plugins/js_composer/assets/css/js_composer.css?ver=4.5" type="text/css" media="all">';
		// loop through posts
		$i = 0;
		while( $loop->have_posts() ): $loop->the_post();
		if( $i == 0 ) { ?>
			
		<div class="vc_row wpb_row vc_row-fluid hero">
			<div class="vc_col-sm-6 wpb_column vc_column_container">
				<a href="<?php the_permalink() ?>">
					<?php the_post_thumbnail( 'large' ) ?>
				</a>
			</div>
			<div class="vc_col-sm-6 wpb_column vc_column_container">
				<a href="<?php the_permalink() ?>">
					<h1><?php echo get_the_title() ?></h1>
					<h2><?php echo get_post_meta( get_the_ID(), 'apparition_subtitle', true ) ?> </h2>
					<h3><?php echo get_post_meta( get_the_ID(), 'apparition_cta', true ) ?></h3>
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
			echo '
			<div class="vc_row wpb_row vc_row-fluid">';
			echo '<div class="vc_col-sm-3 wpb_column vc_column_container">';
			?>
			<a href="<?php the_permalink() ?>">
			
			<?php the_post_thumbnail( 'medium' ) ?>
			</a>
			<?php
			echo '</div>';
			echo '<div class="vc_col-sm-9 wpb_column vc_column_container ">';
			?>
			<a href="<?php the_permalink() ?>">
			<?php
			echo '<h1>' . get_the_title() . '</h1>';
			echo '<p>' . get_the_excerpt() . '</p>';
			echo '</a></div>';
		}
		echo '
		</div>';
		$i++;
		endwhile;
	}

	wp_reset_postdata();

}

genesis();