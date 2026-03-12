<?php
/**
* This class is loaded on the back-end since its main job is
* to display the Admin to box.
*/
class CFWJM_Admin {
	
	public function __construct () {
		
		add_action( 'init', array( $this, 'CFWJM_init' ) );
		add_action( 'admin_menu', array( $this, 'CFWJM_admin_menu' ) );
		add_action('admin_enqueue_scripts', array( $this, 'CFWJM_admin_script' ));
		// Admin dynamic field hooks moved to `CFWJM_Admin_Renderers` class
		// Admin renderers are registered by `CFWJM_Admin_Renderers` class instantiated in the main plugin file
		if ( is_admin() ) {
			return;
		}

		
		
	}
	
	public function CFWJM_admin_script ($hook) {

		if($hook=='toplevel_page_cfwjm-fields'){
			global $CFWJM_Global;
			wp_enqueue_style('wp-components');
			wp_register_script(
	            'cfwjm-react-admin',
	            CFWJM_PLUGINURL.'/build/admin/admin.js', // Adjust the path if necessary
		        ['wp-element','wp-dom-ready','wp-components'], // Ensure this depends on WordPress's React
		        '1.0',
		        true

		        );

			
	        wp_localize_script('cfwjm-react-admin', 'cfwjm_wp_ajax', [
	            'nonce' => wp_create_nonce('wp_rest'), 
	            'get_fields' => rest_url('cfwjm/v1/get_fields'),
	            'add_field' => rest_url('cfwjm/v1/add_field'),
	            'update_field' => rest_url('cfwjm/v1/update_field'),
	            'delete_field' => rest_url('cfwjm/v1/delete_field'),
	            'site_url' => get_site_url(),
	            'fieldset_arr' => $CFWJM_Global['fieldset_arr'],
	            'display_loc_arr' => $CFWJM_Global['display_loc_arr'],
	            
	        ]);

			wp_enqueue_script('cfwjm-react-admin');

		    wp_enqueue_style(
		            'cfwjm-react-admin-style',
		            CFWJM_PLUGINURL.'/build/admin/admin.css',
		            array(),
		            1,
		        );
			
		}

	}

	/**
	 * Dynamically add custom fields (stored as wpjmcf posts) to the Job meta box in admin
	 */
	public function add_dynamic_admin_fields( $fields ) {
		$args = array(
				'post_type' => 'wpjmcf',
				'post_status' => 'publish',
				'meta_key' => 'field_ordernumber_cfwjm',
				'orderby' => 'meta_value_num',
				'order' => 'ASC',
				'posts_per_page' => -1,
		);
		$postslist = get_posts( $args );
		if ( ! empty( $postslist ) ) {
			foreach ( $postslist as $pl ) {
				$post_id = $pl->ID;
				$c_type = get_post_meta( $post_id, 'field_type_cfwjm', true );
				$c_key = '_field_cfwjm' . $post_id;
				$placeholder_meta = get_post_meta( $post_id, 'field_placeholder_cfwjm', true );

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
				$field_arr = array(
					'label'       => $pl->post_title,
					'type'        => $input_type,
					'placeholder' => $placeholder_meta ? $placeholder_meta : $pl->post_title,
					'priority'    => 20,
				);
				// options for select/radio/multiselect
				if ( in_array( $c_type, array( 'select', 'radio', 'multiselect' ) ) ) {
					$field_option_cfwjm = get_post_meta( $post_id, 'field_option_cfwjm', true );
					$field_option_cfwjmar = explode( "\n", $field_option_cfwjm );
					$field_option_cfwjmarr = array();
					foreach ( $field_option_cfwjmar as $valuea ) {
						$valuea = str_replace(array("\r", "\n"), '', $valuea);
						if ( strlen( trim( $valuea ) ) ) {
							$field_option_cfwjmarr[ $valuea ] = $valuea;
						}
					}
					if ( ! empty( $field_option_cfwjmarr ) ) {
						$field_arr['options'] = $field_option_cfwjmarr;
					}
				}
				// required
				$field_required_cfwjm = get_post_meta( $post_id, 'field_required_cfwjm', true );
				if ( $field_required_cfwjm == 'yes' || $field_required_cfwjm == 'on' ) {
					$field_arr['required'] = true;
				}
				$fields[ $c_key ] = $field_arr;
			}
		}
		return $fields;
	}

	public function CFWJM_init () {
		$args = array(
					'label'               => __( 'wpjmcf', 'cfwjm' ),
					'show_ui'             => false,
					'show_in_menu'        => false,
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => false,
					'menu_position'       => 5,
					'can_export'          => true,
					'has_archive'         => true,
					'exclude_from_search' => true,
					'publicly_queryable'  => true,
				);
	
		// Registering your Custom Post Type
		register_post_type( 'wpjmcf', $args );
		
	}
	public function CFWJM_admin_menu () {
		add_menu_page('Custom Field For WP Job Manager', 'Custom Field For WP Job Manager', 'manage_options', 'cfwjm-fields', array( $this, 'CFWJM_page' ));
	}
	public function CFWJM_page() {
		global $CFWJM_Global;
	?>
		<div class="wrap">
			<div class="headingmc">
				<h1 class="wp-heading-inline"><?php _e('WP Job Manager Custom Field', 'cfwjm'); ?></h1>
				<div class="about-text">
			        <p>
						Thank you for using our plugin! If you are satisfied, please reward it a full five-star <span style="color:#ffb900">★★★★★</span> rating.                        <br>
			            <a href="https://wordpress.org/support/plugin/custom-field-for-wp-job-manager/reviews/" target="_blank">Reviews</a>
			            | <a href="https://www.codesmade.com/contact-us/" target="_blank">24x7 Support</a>
			        </p>
			    </div>
			</div>
			<div class="notice notice-success"> 
		            <p>ShortCode <strong>[cm_fieldshow key='_field_cfwjm13' job_id='15']</strong> in that key is mandatory and if you not add <strong>job_id</strong> than take default job post id. </p>
		    </div>
			<?php
			echo '<div id="CFWJM-admin-root"></div>';
			?>

			
		</div>


	<?php
	}



}
?>