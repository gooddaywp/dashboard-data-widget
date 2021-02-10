<?php

namespace dbdw\inc\rest_api_endpoints;

use dbdw\inc\dashboard_data_widget\Dashboard_Data_Widget as Widget;
use WP_REST_Response;
use WP_REST_Request;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

add_action( 'rest_api_init', __NAMESPACE__ . '\custom_endpoints' );

/**
 * Create custom endpoints for block settings
 */
function custom_endpoints() {

    register_rest_route(
        'dbdw/v1',
        'widget-options/',
        array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => __NAMESPACE__ . '\get_widget_options',
            'permission_callback' => '__return_true',
        )
    );

    register_rest_route(
        'dbdw/v1',
        'widget-options/',
        array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => __NAMESPACE__ . '\update_widget_options',
            'permission_callback' => __NAMESPACE__ . '\check_permissions'
        )
    );
}

function get_widget_options() {

    $response = new WP_REST_Response( Widget::get_widget_options() );
    $response->set_status(200);

    return $response;
}

function update_widget_options( WP_REST_Request $request ) {

    $new_options = json_decode( $request->get_body() );
    $success = Widget::update_widget_options( $new_options );

    $response = new WP_REST_Response( array(
        'success' => $success
    ) );
    $response->set_status( $success ? 201 : 200 );

    return $response;
}

function check_permissions() {
    return current_user_can( 'edit_dashboard' );
}
