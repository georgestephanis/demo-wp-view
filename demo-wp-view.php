<?php

/*
Plugin Name: Demo WP View
Plugin URI:  http://github.com/georgestephanis/demo-wp-view
Description: A demo for using Editor Views in WordPress 3.9+
Author:      George Stephanis
Version:     0.1
Author URI:  http://stephanis.info/
*/

function demo_wp_view_shortcode( $args ){
	$defaults = array(
		'content' => __( 'Please specify some content.' ),
	);
	$args = shortcode_atts( $defaults, $args, 'demo_wp_view' );

	return sprintf( '<div class="demo_wp_view">%s</div>', esc_html( wp_kses( $args['content'], array() ) ) );
}
add_shortcode( 'demo_wp_view', 'demo_wp_view_shortcode' );
