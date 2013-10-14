<?php
/**
 * This file adds the Static Content page template to the Apparition Child Theme.
 *
 * @author Chad A. Carino
 * @package Apparition - Tekserve
 * @subpackage Customizations
 */

/*
Template Name: Blog Front Page
*/

// Add custom body class to the head


// Remove header, navigation, breadcrumbs, footer widgets, footer 

remove_action( 'genesis_before_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs');
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
remove_action( 'genesis_post_title', 'genesis_do_post_title' );
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'frontpage_custom_loop' );

function frontpage_custom_loop () 
{
$args = array(
	'posts_per_page' => 1,
	'post__in'  => get_option( 'sticky_posts' ),
	'ignore_sticky_posts' => 1
);
query_posts( $args );
}

genesis();