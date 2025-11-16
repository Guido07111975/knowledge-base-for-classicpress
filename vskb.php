<?php
/*
 * Plugin Name: VS Knowledge Base
 * Description: With this lightweight plugin you can create a knowledge base that contains your categories and posts.
 * Version: 8.3
 * Author: Guido
 * Author URI: https://www.guido.site
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 7.4
 * Requires at least: 6.0
 * Text Domain: very-simple-knowledge-base
 */

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// plugin version number
function vskb_version_number() {
	return '8.3';
}

// enqueue css script
function vskb_css_script() {
	wp_enqueue_style( 'vskb-style', plugins_url( '/css/vskb-style.min.css',__FILE__ ), array(), vskb_version_number() );
}
add_action( 'wp_enqueue_scripts', 'vskb_css_script' );

// register widget
function vskb_register_widget() {
	register_widget( 'vskb_widget' );
}
add_action( 'widgets_init', 'vskb_register_widget' );

// add attributes link
function vskb_action_links( $links ) {
	$attributeslink = array( '<a href="https://wordpress.org/plugins/very-simple-knowledge-base/" target="_blank">'. __( 'Attributes', 'very-simple-knowledge-base' ) .'</a>' );
	return array_merge( $links, $attributeslink );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'vskb_action_links' );

// add body class for default twenty themes
function vskb_body_class( $classes ) {
	$theme = get_option( 'template' );
	if ( 'twentytwenty' == $theme || 'twentytwentyone' == $theme ) {
		$classes[] = 'vskb-twenty';
	}
	return $classes;
}
add_filter( 'body_class', 'vskb_body_class', 11 );

// disable knowledge base in block editor
function vskb_disable_knowledge_base() {
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST && isset( $_GET['context'] ) && ( 'edit' === $_GET['context'] ) ) {
		$disable = true;
	} else {
		$disable = false;
	}
	return $disable;
}

// include files
if ( function_exists( 'register_block_type' ) ) {
	include 'vskb-block.php';
}
include 'vskb-widget.php';
include 'vskb-shortcode.php';
