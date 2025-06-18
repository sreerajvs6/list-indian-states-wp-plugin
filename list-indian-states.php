<?php
/*
Plugin Name: List Indian States
Description: Fetches and displays States of India from an external API.
Version: 1.0
Author: Sreeraj VS
*/

add_shortcode('list_indian_states', 'list_indian_states_output');

function list_indian_states_output($atts) {
    // Allow API URL as shortcode attribute
    $atts = shortcode_atts(array(
        'url' => 'https://cdn-api.co-vin.in/api/v2/admin/location/states'
    ), $atts);

    // Fetch data
    $response = wp_remote_get($atts['url']);

    // Handle errors
    if (is_wp_error($response)) {
        return 'Failed to fetch API data.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!$data) {
        return 'Invalid JSON response.';
    }

    // Render output
    $output = '<div class="state-list">
                <select id="states" name="states" class="form-control">';
    foreach ($data['states'] as $state) {
        $output .= '<option value="'.esc_html($state['state_id']).'">' . esc_html($state['state_name']) . '</option>';
    }
    $output .= '</select>
                </div>';

    return $output;
}
