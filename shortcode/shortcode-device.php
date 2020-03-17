<?php
/**
 * Link shortcode
 *
 * Write [cat_megamenu] in your post editor to render this shortcode.
 */

if ( ! function_exists( 'device_selection_shortcode' ) ) {
	// Add the action.
	add_action( 'plugins_loaded', function() {
		// Add the shortcode.
		add_shortcode( 'device_selection', 'device_selection_shortcode' );
	});

	/**
	 * Shortcode Function
	 */
	function device_selection_shortcode( $atts ) {

		// Save $atts.
		$_atts = shortcode_atts( array(
			'taxonomy' => 'pa_color', //Type of taxonomy
		), $atts ); 

		$options = array('hide_empty' => false);

		$terms = get_terms($_atts['taxonomy'], $options);

		ob_start();

		echo '<select id="custom-select-device" class="custom-select-device" name"custom-select-device">';

		foreach ($terms as $each_term) {
			echo '<option value="'.$each_term->slug.'">'.$each_term->name.'</option>';
		} 		
		
		echo '</select>';

		$output_string = ob_get_contents();

		ob_end_clean();
		
		return $output_string;

	}

}
// End if().
