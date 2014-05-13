<?php
/*
Template Name: Knowledge Base - for Genesis
*/

/**
 * Return the total no of unique post in terms/Categories
 * 
 * @global type $wpdb
 * @param array $term_id
 * @return type
 */

//add script to toggle subcats
add_action( 'genesis_after', 'tekserve_faq_subcat_toggle' ); 

function tekserve_faq_subcat_toggle() {
	echo '<script type="text/javascript">
	jQuery( document ).ready(function( $ ) {
		$(".sub-categories.level-1").each(function(){
			var thisId = ($(this).attr("id")).slice(4);
			var hasKids = $("ul.sub-categories.level-2.parent-"+thisId);
			if(hasKids.length>0){
				$(this).addClass("hasKids");
			}
			else {
				var changeLink = $(this).attr("catlink");
				$(this).find("a.expand-link").attr("href", changeLink);
				$(this).find("i.icon-folder-close").removeClass("icon-folder-close").addClass("icon-chevron-right");
				$(this).children("li.sub-category-l1-link").hide();
			}
		});
		$(".sub-categories.level-1.hasKids").click(function(){
			var thisId = ($(this).attr("id")).slice(4);
			if($(this).hasClass("open")) {
				$(this).find("i.icon-folder-open").removeClass("icon-folder-open").addClass("icon-folder-close");
				$(this).children("li.sub-category-l1-link").fadeIn();
				$(this).removeClass("open");
			}
			else {
				$(this).find("i.icon-folder-close").removeClass("icon-folder-close").addClass("icon-folder-open");
				$(this).children("li.sub-category-l1-link").hide();
				$(this).addClass("open");
			}
			$("ul.sub-categories.level-2.parent-"+thisId).slideToggle();
		});
	});
</script>';
}

//add text to the title
add_action('genesis_post_title', 'tekserve_faq_edition_title');
function tekserve_faq_edition_title() {
	$faq_edition_custom_title = 'Browse By ' . get_the_title();
// 	echo '<div class="wpb_row section flatBottom">
// 		<div class="vc_span12 wpb_column column_container" style="min-height: 0px;">
// 			<div class="wpb_wrapper">';
	echo "<h1 class='entry-title'>".$faq_edition_custom_title."</h1>";
// 	echo '</div></div></div> ';
}

/** Remove Post Info */
remove_action( 'genesis_after_post_title', 'genesis_post_meta' );
remove_action( 'genesis_post_title', 'genesis_do_post_title' );
remove_action( 'genesis_post_content', 'genesis_do_post_content' );
add_action( 'genesis_after_entry', 'genesis_do_post_content' );

function get_total_cat_count($term_id = array()){
    
    global $wpdb;
    
    $result['A'] = 0;
    
    $qry['A']  = " SELECT DISTINCT(B.object_id) FROM {$wpdb->term_taxonomy} A , {$wpdb->term_relationships} B ";
    $qry['A'] .= " WHERE A.term_taxonomy_id=B.term_taxonomy_id AND A.term_id IN (" .  implode(",",$term_id) . ")"; 
    
    
    
    $result['A'] = $wpdb->get_results($qry['A']);
    
    return count($result['A']);
}


//Content
add_action( 'genesis_post_content', 'tekserve_faq_kb_loop' );



function tekserve_faq_kb_loop() {
	$categories = get_categories(array(
		'orderby'         => 'name',
		'order'           => 'ASC',
		'hierarchical'    => true,
		'parent'          => 0,
		'hide_empty'      => false,
	)); 

	$i    = 0;
	$skip = TRUE;
	$content = '';
// 	$cats = print_r($categories,true);
// 	$subcats[] = array();
// 	$si = 0;
	foreach($categories as $category) { 
		if($i++%3==0 && $skip){
			$content .= '<div class="row knowledge-base wpb_row">';
		}
		$skip = TRUE;
	
		$term_id        = array();
		$term_id[]      = $category->term_id;
	
		$sub_categories = get_categories(array(
			'orderby'   => 'name',
			'order'     => 'ASC',
			'child_of'  => $category->cat_ID,
		)); 
		
// 		$subcats[$si] = $sub_categories;
// 		$si++;
	
		$cat_posts = get_posts(array(
			'numberposts'   => -1,
			'category__and'  => $category->term_id,
		));
// 		$subcats[$si] = $cat_posts;
		
// 		$si++;
	
		if(count($sub_categories)==0 && count($cat_posts)==0){
			$i--;
			$skip = FALSE;
			continue;
		}
	
		
		$content .= '<div class="vc_span3 wpb_column column_container span3" style="min-height: 0px;">
			<div class="wpb_wrapper">
			<h2 class="knowledge-base-root-title">
				<a href="' . get_category_link($category->term_id) .' " title="' . $category->name . '">
				' . $category->name . '
				</a>
			</h2>';
		$root_cat = $category->term_id;
		$prev_subcat = '';
		foreach($sub_categories as $sub_category) {  
			$term_id[] = $sub_category->term_id;
			if( $sub_category->parent != $root_cat ) {
				$content .= '<ul id="cat-' . $sub_category->term_id . '" class="sub-categories level-2 parent-' . $sub_category->parent . '" style="display: none;">';
				$content .= '	<li><i class="icon-chevron-right"></i>
					<a href="' . get_category_link( $sub_category->term_id ) . '" title="' . $sub_category->name . '">
						' . $sub_category->name . '
					</a>
				</li>';
			}
			else {
				$content .= '<ul id="cat-' . $sub_category->term_id . '" class="sub-categories level-1" catlink="' . get_category_link( $sub_category->term_id ) . '">';
				$content .= '	<li><i class="icon-folder-close"></i>
					<a class="expand-link" href="javascript:;" title="' . $sub_category->name . '">
						' . $sub_category->name . '
					</a>
				</li>';
				$content .= '<li class="sub-category-l1-link"><i class="icon-chevron-right"></i>
				<a href="' . get_category_link( $sub_category->term_id ) . '" title="See all' . $sub_category->name . ' Articles" >View Articles</a>';
			}
// 			$content .= '<li class="obj">' . print_r($sub_category,true) . '</li>';
			$content .= '</ul>';
			$prev_subcat = $sub_category->term_id;
		}
		
		if( ( count( $cat_posts ) > 0 ) && ( count( $sub_categories ) == 0 ) ){
			$content .= '<ul class="category-posts">';
			$j            = 1;
			$cat_post_num = -1;
			foreach($cat_posts as $post){
				setup_postdata($post);
				switch(get_post_format()){
					case 'video':
						$post_icon = 'icon-film';
						break;
					case 'image':
						$post_icon = 'icon-picture';
						break;
					default:
						$post_icon = 'icon-file-alt';
						break;
				}
				$content .= '<li><i class="' . $post_icon . '"></i><a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></li>';
				if($j++==$cat_post_num) {
					break;
				}
			}
			$content .= '</ul>';
		}
		$content .= '<span class="label label-color root-cat-link">
			<a href="' . get_category_link( $category->term_id ) . '" >';
		$content .= 'View all ' . get_total_cat_count($term_id) . ' articles ';
// 		$content .= 'View All Articles ';
		$content .= '		<i class="icon-chevron-right"></i>
			</a>
		</span>
		</div></div>';
		if($i%3==0){
			$content .= '</div>';
		}
   
	}
	if($i%3!=0){
		$content .= '</div>';
	}
// 	echo $cats . '<hr/>' . print_r($subcats,true) . '<hr/>' . $content;
	echo $content;
}

//Add class
// add_filter( 'body_class', 'tekserve_faq_kb_body_class' );
function tekserve_faq_kb_body_class( $classes ) {
     $classes[] = 'page-template-static_content-php';
     return $classes;
}

genesis();

