<?php

/****
* Template Name: Apparition Post Archive
* Description: Infinite Scroll Blog archive page, headed by splash for latest article.
****/



//replace read more link for autogenerated excerpts with unicode ellipsis
add_filter('excerpt_more', 'apparition_blog_archive_excerpt_more');
function apparition_blog_archive_excerpt_more( $more ) {

	return '&hellip;';

}	//end apparition_blog_archive_excerpt_more( $more )



// Custom Loop creates hero and additional article structure
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'apparition_post_loop' );

function apparition_post_loop() {

	if( get_query_var('paged') ) {
	
		$paged = get_query_var('paged');
	
	}
	elseif( get_query_var('page') ) {
	
		$paged = get_query_var('page');
	
	}
	else {
	
		$paged = 1;
	
	}	//end if( get_query_var('paged') )
	
	//vars for use in query and loop
	$placeholderimg = '<img class="wp-post-image placeholder" alt="No Image Found for This Article" src="' . get_stylesheet_directory_uri() . '/images/blogplaceholder.jpg" />';
	$args = array(
		'orderby'       	=> 'post_date',
		'order'         	=> 'DESC',
		'posts_per_page'	=> '12', // overrides posts per page in theme settings
		'paged' 			=> $paged,
		'meta_query' => array(
								'relation'	=> 'OR',
								array(
									'key' 		=> '_genesis_layout',
									'compare' 	=> 'NOT EXISTS'
								),
								array(
									'key'     => '_genesis_layout',
									'value'   => 'content-sidebar',
									'compare' => 'LIKE',
								),
							)
	);
	
	//create new query
	$loop = new WP_Query( $args );
	if( $loop->have_posts() ) {
		
		echo '<link rel="stylesheet" id="js_composer_front-css" href="'.get_site_url().'/wp-content/plugins/js_composer/assets/css/js_composer.css?ver=4.5" type="text/css" media="all">';
		// loop through posts
		$i = 0;
		while( $loop->have_posts() ): $loop->the_post();
		
			//vars for each article, default if not defined
			$subtitle = get_post_meta( get_the_ID(), 'apparition_post_subtitle', true ) ? '<h2>' . get_post_meta( get_the_ID(), 'apparition_post_subtitle', true ) . '</h2>' : '';
			$cta = get_post_meta( get_the_ID(), 'apparition_post_cta', true ) ? '<h3>' . get_post_meta( get_the_ID(), 'apparition_post_cta', true ) . '</h3>' : '<h3>Read More</h3>';
			
			//define hero structure for latest article and create separator
			if( $i == 0 ) { ?>
			
			<div class="vc_row wpb_row vc_row-fluid hero">
				<div class="vc_col-sm-4 wpb_column vc_column_container">
					<a href="<?php the_permalink() ?>">
						<p class="heroimage"><?php has_post_thumbnail() ? the_post_thumbnail( 'large' ) : print($placeholderimg) ?></p>
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
				$i++;	//$i remains 1 for the rest of the while loop, i.e. only uses else case
		
			}
			
			//standard structure for all previous articles
			else {
		
				?> 
				<div class="vc_row wpb_row vc_row-fluid article-archive">
					<div class="vc_col-sm-3 wpb_column vc_column_container">
						<a href="<?php the_permalink() ?>">
							<?php has_post_thumbnail() ? the_post_thumbnail( 'medium' ) : print($placeholderimg) ?>
						</a>
					</div>
					<div class="vc_col-sm-9 wpb_column vc_column_container">
						<a href="<?php the_permalink() ?>">
							<h1><?php the_title() ?></h1>
							<div class="post-meta"><?php the_time( 'l, F j, Y' ) ?></div>
							<p><?php the_excerpt() ?></p>
							<?php echo $cta ?>
						</a>
					</div>
			<?php
		
			}	//end if( $i == 0 )
			?>
			</div>
		<?php
		
		endwhile;
		//generate nav links for pagination, used for jQuery.infinitescroll
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
	
	}	//end if( $loop->have_posts() )

}	//end apparition_post_loop()



/****
	Infinite Scroll & Hero inits enqueued
****/

add_action( 'wp_enqueue_scripts', 'apparition_infinite_enqueue' );
function apparition_infinite_enqueue() {

	wp_enqueue_script ( 'infinitejs', get_stylesheet_directory_uri() . '/js/jquery.infinitescroll.min.js', array( 'jquery', 'apparitionjs' ) );
	wp_enqueue_script ( 'blogpostarchive', get_stylesheet_directory_uri() . '/js/blogPostArchive.js', array( 'jquery', 'apparitionjs', 'infinitejs' ) );

}	//end apparition_infinite_enqueue()



/****
	Adds Infinite Scroll Body Class
****/

add_filter( 'body_class','apparition_infinite_scroll_body_class' );
function apparition_infinite_scroll_body_class( $classes ) {
 
    $classes[] = 'infinite-scroll';
    return $classes;
     
}	//end apparition_infinite_scroll_body_class( $classes )



/****
	Init Genesis after Creating Loop and Enqueuing Scripts
****/

genesis();