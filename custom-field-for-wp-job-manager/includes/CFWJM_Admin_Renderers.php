<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CFWJM_Admin_Renderers {
    public function __construct() {
        add_action( 'job_manager_input_number', array( $this, 'input_number' ), 10, 3 );
        add_action( 'job_manager_input_range', array( $this, 'input_range' ), 10, 3 );
        add_action( 'job_manager_input_email', array( $this, 'input_email' ), 10, 3 );
        add_action( 'job_manager_input_url', array( $this, 'input_url' ), 10, 3 );
        add_action( 'job_manager_input_telephone', array( $this, 'input_telephone' ), 10, 3 );
        add_filter( 'job_manager_job_listing_data_fields', array( $this, 'add_dynamic_admin_fields' ), 20 );
        add_action( 'job_manager_input_wp_editor', array( $this, 'CFWJM_wp_editor'), 10, 2 );
    }

    public function input_number( $key, $field, $value = null ) {
        if ( is_null( $value ) ) {
            global $post, $thepostid;
            $post_id = ! empty( $post->ID ) ? $post->ID : ( ! empty( $thepostid ) ? $thepostid : 0 );
            $value = $post_id ? get_post_meta( $post_id, $key, true ) : '';
        }

        $min  = isset( $field['min'] ) ? 'min="' . esc_attr( $field['min'] ) . '"' : '';
        $max  = isset( $field['max'] ) ? 'max="' . esc_attr( $field['max'] ) . '"' : '';
        $step = isset( $field['step'] ) ? 'step="' . esc_attr( $field['step'] ) . '"' : '';

        $attrs = '';
        if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
            foreach ( $field['attributes'] as $attr => $val ) {
                $attrs .= esc_attr( $attr ) . '="' . esc_attr( $val ) . '" ';
            }
        }

        ?>
        <p class="form-field form-field-number">
            <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ?? '' ); ?>:</label>
            <?php printf(
                '<input type="number" class="input-text" name="%1$s" id="%1$s" placeholder="%2$s" value="%3$s" %4$s %5$s %6$s %7$s />',
                esc_attr( $key ),
                esc_attr( $field['placeholder'] ?? '' ),
                esc_attr( $value ),
                $min,
                $max,
                $step,
                $attrs
            ); ?>
            <?php if ( ! empty( $field['description'] ) ) : ?>
                <small class="description"><?php echo esc_html( $field['description'] ); ?></small>
            <?php endif; ?>
        </p>
        <?php
    }

    public function input_range( $key, $field, $value = null ) {
        if ( is_null( $value ) ) {
            global $post, $thepostid;
            $post_id = ! empty( $post->ID ) ? $post->ID : ( ! empty( $thepostid ) ? $thepostid : 0 );
            $value = $post_id ? get_post_meta( $post_id, $key, true ) : '';
        }

        $min  = isset( $field['min'] ) ? 'min="' . esc_attr( $field['min'] ) . '"' : '';
        $max  = isset( $field['max'] ) ? 'max="' . esc_attr( $field['max'] ) . '"' : '';
        $step = isset( $field['step'] ) ? 'step="' . esc_attr( $field['step'] ) . '"' : '';

        $attrs = '';
        if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
            foreach ( $field['attributes'] as $attr => $val ) {
                $attrs .= esc_attr( $attr ) . '="' . esc_attr( $val ) . '" ';
            }
        }

        ?>
        <p class="form-field form-field-range">
            <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ?? '' ); ?>:</label>
            <?php printf(
                '<input type="range" class="input-range" name="%1$s" id="%1$s" value="%3$s" %4$s %5$s %6$s %7$s />',
                esc_attr( $key ),
                esc_attr( $field['placeholder'] ?? '' ),
                esc_attr( $value ),
                $min,
                $max,
                $step,
                $attrs
            ); ?>
            <?php if ( ! empty( $field['description'] ) ) : ?>
                <small class="description"><?php echo esc_html( $field['description'] ); ?></small>
            <?php endif; ?>
        </p>
        <?php
    }

    public function input_email( $key, $field, $value = null ) {
        if ( is_null( $value ) ) {
            global $post, $thepostid;
            $post_id = ! empty( $post->ID ) ? $post->ID : ( ! empty( $thepostid ) ? $thepostid : 0 );
            $value = $post_id ? get_post_meta( $post_id, $key, true ) : '';
        }

        $attrs = '';
        if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
            foreach ( $field['attributes'] as $attr => $val ) {
                $attrs .= esc_attr( $attr ) . '="' . esc_attr( $val ) . '" ';
            }
        }

        ?>
        <p class="form-field form-field-email">
            <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ?? '' ); ?>:</label>
            <?php printf(
                '<input type="email" class="input-text" name="%1$s" id="%1$s" placeholder="%2$s" value="%3$s" %4$s />',
                esc_attr( $key ),
                esc_attr( $field['placeholder'] ?? '' ),
                esc_attr( $value ),
                $attrs
            ); ?>
            <?php if ( ! empty( $field['description'] ) ) : ?>
                <small class="description"><?php echo esc_html( $field['description'] ); ?></small>
            <?php endif; ?>
        </p>
        <?php
    }

    public function input_url( $key, $field, $value = null ) {
        if ( is_null( $value ) ) {
            global $post, $thepostid;
            $post_id = ! empty( $post->ID ) ? $post->ID : ( ! empty( $thepostid ) ? $thepostid : 0 );
            $value = $post_id ? get_post_meta( $post_id, $key, true ) : '';
        }

        $attrs = '';
        if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
            foreach ( $field['attributes'] as $attr => $val ) {
                $attrs .= esc_attr( $attr ) . '="' . esc_attr( $val ) . '" ';
            }
        }

        ?>
        <p class="form-field form-field-url">
            <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ?? '' ); ?>:</label>
            <?php printf(
                '<input type="url" class="input-text" name="%1$s" id="%1$s" placeholder="%2$s" value="%3$s" %4$s />',
                esc_attr( $key ),
                esc_attr( $field['placeholder'] ?? '' ),
                esc_attr( $value ),
                $attrs
            ); ?>
            <?php if ( ! empty( $field['description'] ) ) : ?>
                <small class="description"><?php echo esc_html( $field['description'] ); ?></small>
            <?php endif; ?>
        </p>
        <?php
    }

    public function input_telephone( $key, $field, $value = null ) {
        if ( is_null( $value ) ) {
            global $post, $thepostid;
            $post_id = ! empty( $post->ID ) ? $post->ID : ( ! empty( $thepostid ) ? $thepostid : 0 );
            $value = $post_id ? get_post_meta( $post_id, $key, true ) : '';
        }

        $attrs = '';
        if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
            foreach ( $field['attributes'] as $attr => $val ) {
                $attrs .= esc_attr( $attr ) . '="' . esc_attr( $val ) . '" ';
            }
        }

        ?>
        <p class="form-field form-field-telephone">
            <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ?? '' ); ?>:</label>
            <?php printf(
                '<input type="tel" class="input-text" name="%1$s" id="%1$s" placeholder="%2$s" value="%3$s" %4$s />',
                esc_attr( $key ),
                esc_attr( $field['placeholder'] ?? '' ),
                esc_attr( $value ),
                $attrs
            ); ?>
            <?php if ( ! empty( $field['description'] ) ) : ?>
                <small class="description"><?php echo esc_html( $field['description'] ); ?></small>
            <?php endif; ?>
        </p>
        <?php
    }
    public function CFWJM_wp_editor( $key, $field ){
		global $thepostid;
		if ( empty( $field['value'] ) ) {
		    $field['value'] = get_post_meta( $thepostid, $key, true );
		}
		$name = ! empty( $field['name']) ? $field['name'] : $key;
		$args = array(
				'textarea_rows' => 4,
				'textarea_name' => $name,
				'media_buttons' => FALSE,
				'editor_class'	=> ''
		);
		?>
		<div class="form-field form-field-wp_editor" >
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?>:</label>
				<?php wp_editor( $field['value'], esc_attr( $key ), $args ); ?>
		</div>
		<?php

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
                            $input_type = 'wp_editor';
                            break;
                        default:
                            $input_type = 'text';
                    }
                $field_arr = array(
                    'label'       => $pl->post_title,
                    'type'        => $input_type,
                    'placeholder' => $placeholder_meta ? $placeholder_meta : $pl->post_title,
                    'class'       => get_post_meta( $post_id, 'field_class_cfwjm', true ),
                    'min'         => get_post_meta( $post_id, 'field_min_cfwjm', true ),
                    'max'         => get_post_meta( $post_id, 'field_max_cfwjm', true ),
                    'step'        => get_post_meta( $post_id, 'field_step_cfwjm', true ),
                    'description' => get_post_meta( $post_id, 'field_description_cfwjm', true ),
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
                $field_hide_admin = get_post_meta( $post_id, 'field_hide_admin_cfwjm', true );
                $field_hide_frontend = get_post_meta( $post_id, 'field_hide_frontend_cfwjm', true );

                // If this field is set to be hidden in admin, skip adding it to admin fields
                if ( $field_hide_admin === 'yes' || $field_hide_admin === true ) {
                    continue;
                }
                if ( $field_required_cfwjm == 'yes' || $field_required_cfwjm == 'on' ) {
                    $field_arr['required'] = true;
                }
                // expose hide flags and description in admin field array for renderers
                $field_arr['hide_admin'] = ( $field_hide_admin === 'yes' );
                $field_arr['hide_frontend'] = ( $field_hide_frontend === 'yes' );
                $field_arr['description'] = get_post_meta( $post_id, 'field_description_cfwjm', true );
                $fields[ $c_key ] = $field_arr;
            }
        }
        return $fields;
    }

   
}

