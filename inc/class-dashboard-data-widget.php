<?php
/**
 * Dashboard Data Widget
 *
 * @link https://developer.wordpress.org/apis/handbook/dashboard-widgets/
 *
 * @package dbdw
 */

namespace dbdw\inc\dashboard_data_widget;

defined( 'ABSPATH' ) || exit;

add_action( 'wp_dashboard_setup', array( __NAMESPACE__ . '\Dashboard_Data_Widget', 'init' ) );

class Dashboard_Data_Widget {

    /**
     * The id of this widget.
     */
    const ID = 'dbdw_dashboard_data_widget';

    /**
     * Hook to wp_dashboard_setup to add the widget.
     */
    public static function init() {

        // delete_option( DBDW_OPTIONS_ID );

        // Register widget options if they do not already exist by setting autoload to `true`.
        self::update_widget_options( array(), true );

        // Register the widget.
        wp_add_dashboard_widget(
            self::ID,
            __( 'Dashboard Data Widget', 'dbdw' ),
            array( __CLASS__, 'widget' ),
            array( __CLASS__, 'config' ),
            null,
            'normal',
            'high'
        );
    }

    /**
     * Load the widget code
     */
    public static function widget() {

        if ( intval(self::get_widget_option('widget_disabled', 0)) > 0 ) {

            $msg = force_balance_tags( sprintf(
                /* translators: %s: url to configuration screen; please do not alter or remove <a> tags */
                __( "The widget is currently disabled. You can enable it via the <a href='%s'>widget configuration screen</a>.", 'dbdw' ),
                admin_url( 'index.php?edit=' . self::ID .'#'. self::ID )
            ) );
            echo "<div>$msg</div>";

        } else {
            echo "<div id='dbdw-dashboard-data-widget-root'></div>";
        }
    }

    /**
     * Load widget config code.
     *
     * This is what will display when an admin clicks
     */
    public static function config() {

        if ( self::is_valid_config_form_submission() ) {
            self::update_widget_options( array(
                'widget_disabled' => isset( $_POST['dbdw-config-disable-widget'] ) ? 1 : 0,
            ) );
        }

        $checked = checked(1, self::get_widget_option('widget_disabled', 0), false);

        echo join( '', array(
            '<p>',
            __( 'This is an example of a dashboard widget configuration screen. If you disable the widget, the widget will show a "widget disabled" message instead of the React JS graph component.', 'dbdw' ),
            '</p>',
            "<p><label><input type='checkbox' name='dbdw-config-disable-widget' $checked /> Disable Widget</label></p>",
        ) );
    }

    /**
     * Gets all options of this widget.
     */
    public static function get_widget_options() {

        $default_options = require_once DBDW_PLUGIN_DIR . 'inc/default-options.php';

        return maybe_unserialize( get_option( DBDW_OPTIONS_ID, $default_options ) );
    }

    /**
     * Gets one specific option for the specified widget.
     *
     * @param $option The option name.
     * @param null $default The default value if the option was not found.
     *
     * @return mixed
     */
    public static function get_widget_option( $option, $default=null ) {

        if ( ! $opts = self::get_widget_options() ) {
            return false;
        }
        return isset( $opts[$option] ) ? $opts[$option] : $default;
    }

    /**
     * Saves an array of options for this dashboard widget to the database.
     *
     * @param array $args An associative array of the new options to be saved.
     * @param bool $autoload If true, options will not be added if they already exist.
     */
    public static function update_widget_options( $new_options, $autoload=null ) {

        if ( ! current_user_can( 'edit_dashboard' ) ) {
            return false;
        }

        $sanitized_options = self::sanitize_widget_options( $new_options );
        $current_options   = self::cast_array_recursive( self::get_widget_options() );

        return update_option(
            DBDW_OPTIONS_ID,
            maybe_serialize( wp_parse_args(
                $sanitized_options,
                $current_options
            ) ),
            $autoload
        );
    }

    /**
     * Check if the config form is cleared for processing.
     */
    public static function is_valid_config_form_submission() {

        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
            return false;
        }

        if ( empty( $_POST['widget_id'] ) || $_POST['widget_id'] !== self::ID ) {
            return false;
        }

        $nonce_action_name = 'edit-dashboard-widget_' . self::ID;
        if ( ! wp_verify_nonce( $_POST['dashboard-widget-nonce'], $nonce_action_name ) ) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize form values before saving them to the database.
     *
     * @param array $values The submitted to `update_widget_options`.
     * @return array The sanitized data.
     */
    public static function sanitize_widget_options( $options ) {

        $sanitized_options = [];

        if ( is_object( $options ) ) {
            $options = self::cast_array_recursive( $options );
        }

        foreach ( $options as $k => $v ) {
            switch ( $k ) {
                case 'widget_disabled':
                    $sanitized_options[ $k ] = absint( sanitize_text_field( $v ) );
                    break;
                case 'charts':
                case 'chart_lines':
                    $sanitized_options[ $k ] = self::sanitize_charts( self::cast_array_recursive( $v ) );
                    break;
                case 'timeframe':
                    $sanitized_options[ $k ] = sanitize_text_field( $v );
                    break;
            }
        }

        return $sanitized_options;
    }

    public static function sanitize_charts( $charts ) {
        $sanitized_charts = array();
        foreach ( $charts as $key => $value ) {
            $key = absint( $key );
            $sanitized_charts[ $key ] = (array) $value;
            foreach ( $sanitized_charts[ $key ] as $k => $v ) {
                $sanitized_charts[ $key ][ sanitize_text_field( $k ) ] = $v;
            }
        }
    }

    // https://stackoverflow.com/a/16111687/830992
    public static function cast_array_recursive( $object ) {
        if ( is_object( $object ) ) {
            return json_decode( wp_json_encode( $object ), true );
        }
        return $object;
    }
}

