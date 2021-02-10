<?php

defined( 'ABSPATH' ) || exit;

/**
 * Get asset file.
 *
 * @param string $handle Asset handle to reference.
 * @param string $key What do we want to return: version or dependencies.
 */
function dbdw_asset_file_data( $handle, $key ) {
	$default_asset_file = array(
		'dependencies' => array(),
		'version'      => '1.0',
	);

	$asset_filepath = DBDW_PLUGIN_DIR . "/build/{$handle}.asset.php";
	$asset_file     = file_exists( $asset_filepath ) ? include $asset_filepath : $default_asset_file;

	if ( 'version' === $key ) {
		return $asset_file['version'];
	}

	if ( 'dependencies' === $key ) {
		return $asset_file['dependencies'];
	}
}
