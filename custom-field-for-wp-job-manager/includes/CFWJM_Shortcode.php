<?php
/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class CFWJM_Shortcode {
	
	public function __construct () {
		add_shortcode( 'cm_fieldshow', array( $this, 'cm_fieldshow' ) );
	}
	
	function cm_fieldshow($atts, $content = ""){
		// Ensure attributes are set
		$atts = shortcode_atts(array(
			'key'    => '',
			'job_id' => ''
		), $atts);

		// Check if key is provided
		if (empty($atts['key'])) {
			return 'Please Enter Key in Shortcode';
		}

		// Get job ID
		global $post;
		$jobid = !empty($atts['job_id']) ? $atts['job_id'] : $post->ID;

		// Validate post type
		if (get_post_type($jobid) != 'job_listing') {
			return 'Post Type is Not Correct';
		}

		// Get field value
		$c_value = get_post_meta($jobid, $atts['key'], true);

		// Handle array values
		if (is_array($c_value)) {
			$c_value = implode(', ', $c_value);
		}

		// Format output with allowed HTML
		return sprintf('<span class="cfwjm_output_shortcode">%s</span>', wp_kses_post($c_value));
	}
}
