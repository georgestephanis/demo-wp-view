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

	return sprintf( '<div class="demo_wp_view" style="background: #f00;">%s</div>', esc_html( wp_kses( $args['content'], array() ) ) );
}
add_shortcode( 'demo_wp_view', 'demo_wp_view_shortcode' );

function demo_wp_view_js_template() {
	if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' ) {
		return;
	}
	?>
	<script type="text/html" id="tmpl-demo_wp_view_shortcode">
		<div class="demo_wp_view" style="background: #f00;">{{ data.content }}</div>
	</script>
	<?php
}
add_action( 'admin_print_footer_scripts', 'demo_wp_view_js_template' );

/**
 * For convenience and readability, this is printed out in the
 * footer, but ideally should be enqueued seperately.
 */
function demp_wp_view_footer_scripts() {
	if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' ) {
		return;
	}
	?>
	<script>
		/* global tinyMCE, console */
		(function( $, wp ){
			wp.mce = wp.mce || {};
			wp.mce.demo_wp_view_renderer = {
				shortcode_data : {},
				View : {
					template   : wp.template( 'demo_wp_view_shortcode' ),
					initialize : function( options ) {
						this.shortcode = options.shortcode;
						// Do any needed tweaking here
						wp.mce.demo_wp_view_renderer.shortcode_data = this.shortcode;
					},
					getHtml : function() {
						var options = this.shortcode.attrs.named;
						// Do any needed tweaking here
						return this.template( options );
					}
				},
				edit: function( node ) {
					var values = this.shortcode_data.attrs.named;
					// Do any needed tweaking here
					wp.mce.demo_wp_view_renderer.popupwindow( tinyMCE.activeEditor, values );
				},
				popupwindow: function( editor, values, onsubmit_callback ){
					if ( typeof onsubmit_callback != 'function' ) {
						onsubmit_callback = function( e ) {
							var s = '[demo_wp_view',
								i;
							for ( i in e.data ) {
								if ( e.data.hasOwnProperty( i ) ) {
									s += ' ' + i + '="' + e.data[ i ] + '"';
								}
							}
							s += ']';
							editor.insertContent( s );
						};
					}
					editor.windowManager.open( {
						title : 'Demo WP View', // This should be internationalized via wp_localize_script
						body  : [
							{
								type  : 'textbox',
								name  : 'content',
								label : 'Content', // This should be internationalized via wp_localize_script
								value : values.content
							}
						],
						onsubmit : onsubmit_callback
					} );
				}
			};
			wp.mce.views.register( 'demo_wp_view', wp.mce.demo_wp_view_renderer );
		}( jQuery, wp ));
	</script>
	<?php
}
add_action( 'admin_print_footer_scripts', 'demp_wp_view_footer_scripts' );
