<?php
/**
 * Run code relevant to plugin uninstallation.
 *
 * @link https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
 */

namespace dbdw\inc\plugin_uninstallation;

defined( 'ABSPATH' ) || exit;

delete_option( DBDW_OPTIONS_ID );
