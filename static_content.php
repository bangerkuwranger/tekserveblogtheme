<?php
/**
 * This file adds the Static Content page template to the Apparition Child Theme.
 *
 * @author Chad A. Carino
 * @package Apparition - Tekserve
 * @subpackage Customizations
 */

/*
Template Name: Static Content
*/

// Add custom body class to the head


// Remove header, navigation, breadcrumbs, footer widgets, footer 

remove_action( 'genesis_before_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs');
// remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
remove_action( 'genesis_post_title', 'genesis_do_post_title' );

add_action('genesis_meta', 'blogFrontCode');
function blogFrontCode() {
    wp_register_script( 'blogFront', get_stylesheet_directory_uri() . '/js/blogFront.js' );
    wp_enqueue_script( 'blogFront' );
}

genesis();