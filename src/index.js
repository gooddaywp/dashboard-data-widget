/**
 * WordPress dependencies
 */
import { render } from '@wordpress/element';

/**
 * Main widget component
 */
import Widget from './Widget';

// Render DOM
const elem = document.getElementById( 'dbdw-dashboard-data-widget-root' );
if ( elem ) {
	render( <Widget />, elem );
}
