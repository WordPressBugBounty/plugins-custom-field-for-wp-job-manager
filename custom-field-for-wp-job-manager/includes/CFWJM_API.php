<?php

if (!defined('ABSPATH')) exit;

class CFWJM_API {
    
    public function __construct() {
        add_action('rest_api_init', array($this, 'CFWJM_rest_api_init'));
    }

    public function CFWJM_rest_api_init() {
        register_rest_route('cfwjm/v1', '/get_fields', array(
            'methods'  => 'GET',
            'callback' => array($this, 'CFWJM_get_fields'),
            'permission_callback' => '__return_true', // Public access for GET
        ));

        register_rest_route('cfwjm/v1', '/add_field', array(
            'methods'  => 'POST',
            'callback' => array($this, 'CFWJM_add_field'),
            'permission_callback' => array($this, 'CFWJM_permission_callback'),
        ));

        register_rest_route('cfwjm/v1', '/update_field', array(
            'methods'  => 'POST',
            'callback' => array($this, 'CFWJM_update_field'),
            'permission_callback' => array($this, 'CFWJM_permission_callback'),
        ));

        register_rest_route('cfwjm/v1', '/delete_field', array(
            'methods'  => 'POST',
            'callback' => array($this, 'CFWJM_delete_field'),
            'permission_callback' => array($this, 'CFWJM_permission_callback'),
        ));
    }

    public function CFWJM_get_fields($request) {
        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Invalid nonce',
        ), 403); // Invalid nonce error
        }

        $response = array();
        $fields = get_posts(array(
            'post_type'      => 'wpjmcf',
            'post_status'    => 'publish',
            'posts_per_page' => -1
        ));

        foreach ($fields as $field) {
            $id = $field->ID;
            $postdata = get_post($id);
            $response[] = array(
                'id'                  => $id,
                'postdata'              => $postdata,
                'field_title'         => $field->post_title,
                'field_type_cfwjm'    => get_post_meta($id, 'field_type_cfwjm', true),
                'field_location_cfwjm'=> get_post_meta($id, 'field_location_cfwjm', true),
                'field_placeholder_cfwjm' => get_post_meta($id, 'field_placeholder_cfwjm', true),
                'field_class_cfwjm' => get_post_meta($id, 'field_class_cfwjm', true),
                'field_min_cfwjm' => get_post_meta($id, 'field_min_cfwjm', true),
                'field_max_cfwjm' => get_post_meta($id, 'field_max_cfwjm', true),
                'field_step_cfwjm' => get_post_meta($id, 'field_step_cfwjm', true),
                'field_description_cfwjm' => get_post_meta($id, 'field_description_cfwjm', true),
                'field_hide_admin_cfwjm' => get_post_meta($id, 'field_hide_admin_cfwjm', true),
                'field_hide_frontend_cfwjm' => get_post_meta($id, 'field_hide_frontend_cfwjm', true),
                'field_location_show_cfwjm' => get_post_meta($id, 'field_location_show_cfwjm', true),
                'field_ordernumber_cfwjm' => get_post_meta($id, 'field_ordernumber_cfwjm', true),
                'field_required_cfwjm' => get_post_meta($id, 'field_required_cfwjm', true) === "yes" ? "yes" : "no",
                'field_option_cfwjm'  => get_post_meta($id, 'field_option_cfwjm', true),
                'field_output_cfwjm'  => get_post_meta($id, 'field_output_cfwjm', true),
                'field_use_output_cfwjm' => get_post_meta($id, 'field_use_output_cfwjm', true),
            );
        }

        return rest_ensure_response($response);
    }

    public function CFWJM_add_field($request) {
        $params = $request->get_json_params();

        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Invalid nonce',
        ), 403); // Invalid nonce error
        }
        $settings = $request->get_param('settings');
        if (empty($settings['field_title'])) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Field Name is required.',
            ], 400);
        }

        if (empty($settings['field_ordernumber_cfwjm']) || !is_numeric($settings['field_ordernumber_cfwjm'])) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Field Order Number is required and must be a number.',
            ], 400);
        }
        $post_id = wp_insert_post(array(
            'post_title'  => sanitize_text_field($settings['field_title']),
            'post_type'   => 'wpjmcf',
            'post_status' => 'publish'
        ));

        if ($post_id) {
            update_post_meta($post_id, 'field_type_cfwjm', sanitize_text_field($settings['field_type_cfwjm']));
            update_post_meta($post_id, 'field_location_cfwjm', sanitize_text_field($settings['field_location_cfwjm']));
            update_post_meta($post_id, 'field_placeholder_cfwjm', sanitize_text_field($settings['field_placeholder_cfwjm']));
            update_post_meta($post_id, 'field_class_cfwjm', sanitize_text_field($settings['field_class_cfwjm']));
            update_post_meta($post_id, 'field_min_cfwjm', sanitize_text_field($settings['field_min_cfwjm']));
            update_post_meta($post_id, 'field_max_cfwjm', sanitize_text_field($settings['field_max_cfwjm']));
            update_post_meta($post_id, 'field_step_cfwjm', sanitize_text_field($settings['field_step_cfwjm']));
            update_post_meta($post_id, 'field_description_cfwjm', sanitize_textarea_field($settings['field_description_cfwjm']));
            update_post_meta($post_id, 'field_hide_admin_cfwjm', !empty($settings['field_hide_admin_cfwjm']) ? 'yes' : 'no');
            update_post_meta($post_id, 'field_hide_frontend_cfwjm', !empty($settings['field_hide_frontend_cfwjm']) ? 'yes' : 'no');
            update_post_meta($post_id, 'field_location_show_cfwjm', sanitize_text_field($settings['field_location_show_cfwjm']));
            update_post_meta($post_id, 'field_ordernumber_cfwjm', sanitize_text_field($settings['field_ordernumber_cfwjm']));
            update_post_meta($post_id, 'field_required_cfwjm', $settings['field_required_cfwjm'] ? "yes" : "no");
            update_post_meta($post_id, 'field_option_cfwjm', sanitize_textarea_field($settings['field_option_cfwjm']));
            update_post_meta($post_id, 'field_output_cfwjm', wp_kses_post($settings['field_output_cfwjm']));
            update_post_meta($post_id, 'field_use_output_cfwjm', !empty($settings['field_use_output_cfwjm']) ? 'yes' : 'no');
            return new WP_REST_Response(array('success' => true, 'message' => 'Field added successfully', 'post_id' => $post_id), 200);
        } else {
            return new WP_REST_Response(array('success' => false, 'message' => 'Error adding field'), 500);
        }
    }

    public function CFWJM_update_field($request) {
        $params = $request->get_json_params();

        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Invalid nonce',
        ), 403); // Invalid nonce error
        }
        $settings = $request->get_param('settings');
        $post_id = intval($settings['id']);
        if (!$post_id || get_post_type($post_id) !== 'wpjmcf') {
            return new WP_REST_Response(array('success' => false, 'message' => 'Invalid field ID'), 400);
        }
        

        if (empty($settings['field_title'])) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Field Name is required.',
            ], 400);
        }

        if (empty($settings['field_ordernumber_cfwjm']) || !is_numeric($settings['field_ordernumber_cfwjm'])) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Field Order Number is required and must be a number.',
            ], 400);
        }

        wp_update_post(array(
            'ID'         => $post_id,
            'post_title' => sanitize_text_field($settings['field_title']),
        ));

        update_post_meta($post_id, 'field_type_cfwjm', sanitize_text_field($settings['field_type_cfwjm']));
        update_post_meta($post_id, 'field_placeholder_cfwjm', sanitize_text_field($settings['field_placeholder_cfwjm']));
        update_post_meta($post_id, 'field_class_cfwjm', sanitize_text_field($settings['field_class_cfwjm']));
        update_post_meta($post_id, 'field_min_cfwjm', sanitize_text_field($settings['field_min_cfwjm']));
        update_post_meta($post_id, 'field_max_cfwjm', sanitize_text_field($settings['field_max_cfwjm']));
        update_post_meta($post_id, 'field_step_cfwjm', sanitize_text_field($settings['field_step_cfwjm']));
        update_post_meta($post_id, 'field_description_cfwjm', sanitize_textarea_field($settings['field_description_cfwjm']));
        update_post_meta($post_id, 'field_hide_admin_cfwjm', !empty($settings['field_hide_admin_cfwjm']) ? 'yes' : 'no');
        update_post_meta($post_id, 'field_hide_frontend_cfwjm', !empty($settings['field_hide_frontend_cfwjm']) ? 'yes' : 'no');
        update_post_meta($post_id, 'field_ordernumber_cfwjm', sanitize_text_field($settings['field_ordernumber_cfwjm']));
        update_post_meta($post_id, 'field_location_cfwjm', sanitize_text_field($settings['field_location_cfwjm']));
        update_post_meta($post_id, 'field_location_show_cfwjm', sanitize_text_field($settings['field_location_show_cfwjm']));
        update_post_meta($post_id, 'field_required_cfwjm', $settings['field_required_cfwjm'] ? "yes" : "no");
        update_post_meta($post_id, 'field_option_cfwjm', sanitize_textarea_field($settings['field_option_cfwjm']));
        update_post_meta($post_id, 'field_output_cfwjm', wp_kses_post($settings['field_output_cfwjm']));
        update_post_meta($post_id, 'field_use_output_cfwjm', !empty($settings['field_use_output_cfwjm']) ? 'yes' : 'no');

        return new WP_REST_Response(array('success' => true, 'message' => 'Field updated successfully'), 200);
    }

    public function CFWJM_delete_field($request) {
        $params = $request->get_json_params();
        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Invalid nonce',
        ), 403); // Invalid nonce error
        }
        $settings = $request->get_param('settings');
        $post_id = intval($settings['id']);

        if (!$post_id || get_post_type($post_id) !== 'wpjmcf') {
            return new WP_REST_Response(array('success' => false, 'message' => 'Invalid field ID'), 400);
        }

        $deleted = wp_delete_post($post_id, true); // true for permanent delete

        if ($deleted) {
            return new WP_REST_Response(array('success' => true, 'message' => 'Field deleted successfully'), 200);
        } else {
            return new WP_REST_Response(array('success' => false, 'message' => 'Error deleting field'), 500);
        }
    }

    public function CFWJM_permission_callback($request) {
        return current_user_can('manage_options'); // Allow only admins
    }
}
