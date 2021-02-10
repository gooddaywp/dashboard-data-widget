/**
 * WordPress dependencies
 * https://developer.wordpress.org/block-editor/packages/packages-api-fetch/
 */
import apiFetch from '@wordpress/api-fetch';

const { dbdw } = window;

// Set nonce and root url middleware.
apiFetch.use( apiFetch.createNonceMiddleware( dbdw.restNonce ) );
apiFetch.use( apiFetch.createRootURLMiddleware( dbdw.wpRestUrl ) );

const getWidgetOptions = () => {
	return apiFetch( {
		path: 'dbdw/v1/widget-options',
	} )
		.then( ( response ) => response )
		.catch( ( err ) => err );
};

const setWidgetOptions = ( data ) => {
	return apiFetch( {
		method: 'POST',
		path: 'dbdw/v1/widget-options',
		data,
	} )
		.then( ( response ) => response )
		.catch( ( err ) => err );
};

export { getWidgetOptions, setWidgetOptions };
