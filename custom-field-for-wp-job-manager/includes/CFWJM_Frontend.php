<?php
/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class CFWJM_Frontend {
	public $fieldset_inarr = array();
	public function __construct () {
		$this->fieldset_inarr = array('select','radio','multiselect');
		add_filter( 'submit_job_form_fields', array( $this, 'form_fields' ) );
	}

	function form_fields( $fields ) {
		$args = array(
					    'post_type' => 'wpjmcf',
						'post_status' => 'publish',
						'meta_key'       => 'field_ordernumber_cfwjm',
						'orderby'       => 'meta_value_num',
						'order'       => 'ASC',
						'posts_per_page' => -1
					);
		$postslist = get_posts( $args );
		if (!empty($postslist)) {
			foreach ($postslist as $postslistk => $postslistv) {
				
					
				
					$post_id = $postslistv->ID;
					$c_type = get_post_meta( $post_id, 'field_type_cfwjm', true );
					$c_location = get_post_meta( $post_id, 'field_location_cfwjm', true );
					$c_key = 'field_cfwjm'.$post_id;
					$placeholder_meta = get_post_meta( $post_id, 'field_placeholder_cfwjm', true );
					// Map custom types to HTML input types when needed
					$input_type = '';
					switch ($c_type) {
						case 'number':
							$input_type = 'number';
							break;
						case 'range':
							$input_type = 'range';
							break;
						case 'email':
							$input_type = 'email';
							break;
						case 'url':
							$input_type = 'url';
							break;
						case 'telephone':
							$input_type = 'tel';
							break;
						case 'select':
							$input_type = 'select';
							break;
						case 'multiselect':
							$input_type = 'multiselect';
							break;
						case 'radio':
							$input_type = 'radio';
							break;
						case 'checkbox':
							$input_type = 'checkbox';
							break;
						case 'file':
							$input_type = 'file';
							break;
						case 'textarea':
							$input_type = 'textarea';
							break;
						case 'wp_editor':
							$input_type = 'wp-editor';
							break;	
						default:
							$input_type = 'text';
					}
					//print_r($input_type);
					//echo $input_type.$postslistv->post_title;
					$field_hide_frontend = get_post_meta( $post_id, 'field_hide_frontend_cfwjm', true );

					// If field is set to be hidden on frontend, skip it
					if ( $field_hide_frontend === 'yes' || $field_hide_frontend === true ) {
						continue;
					}

					$field_arr = array(
						'label'       => $postslistv->post_title,
						// Keep WPJM template 'type' for template lookup; use custom_attributes to change HTML input type
						// Treat numeric/email/url/range/telephone as HTML input types (not separate template types)
						'type'        => $input_type,
						'placeholder' => $placeholder_meta ? $placeholder_meta : $postslistv->post_title,
						'class'       => get_post_meta( $post_id, 'field_class_cfwjm', true ),
						'description' => get_post_meta( $post_id, 'field_description_cfwjm', true ),
						'priority'    => 20,
					);

					// read numeric constraints from post meta
					$field_min = get_post_meta( $post_id, 'field_min_cfwjm', true );
					$field_max = get_post_meta( $post_id, 'field_max_cfwjm', true );
					$field_step = get_post_meta( $post_id, 'field_step_cfwjm', true );

					if ( $input_type ) {
						$attrs = array( 'type' => $input_type );
						if ( '' !== $field_min && ! is_null( $field_min ) ) {
							$attrs['min'] = $field_min;
						}
						if ( '' !== $field_max && ! is_null( $field_max ) ) {
							$attrs['max'] = $field_max;
						}
						if ( '' !== $field_step && ! is_null( $field_step ) ) {
							$attrs['step'] = $field_step;
						}
						$field_arr['custom_attributes'] = $attrs;
					}
					$fields[ $c_location ][$c_key] = $field_arr;
				$field_required_cfwjm = get_post_meta( $post_id, 'field_required_cfwjm', true );
				if($field_required_cfwjm=='on'){
					$fields[ $c_location ][$c_key]['required'] = true;
				}else{
					$fields[ $c_location ][$c_key]['required'] = false;
				}
				if($fields[$c_location ][$c_key]['type']=='wp_editor'){
					$fields[$c_location ][$c_key]['type']='wp_editor';
				}
				if (in_array($c_type, $this->fieldset_inarr)) {
					$field_option_cfwjm = get_post_meta( $post_id, 'field_option_cfwjm', true );
					$field_option_cfwjmar = explode("\n", $field_option_cfwjm);
					$field_option_cfwjmarr = array();
					foreach ($field_option_cfwjmar as $keya => $valuea) {
						//$valuea = trim($valuea,"\n");
						$valuea = str_replace(array("\r", "\n"), '', $valuea);
						$field_option_cfwjmarr[$valuea]=$valuea;
					}
					//print_r($field_option_cfwjmarr);
					$fields[ $c_location ][$c_key]['options']=$field_option_cfwjmarr;
					//$fields[ $c_location ][$c_key]['value']=array('as');
				}
				}

			}
		
		
		return $fields;
	}

}	
