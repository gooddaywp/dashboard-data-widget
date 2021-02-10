/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';

/**
 * Main widget component
 */
import Layout from './components/Layout';
import Header from './components/Header';
import TimeframeSelect from './components/TimeframeSelect';
import Chart from './components/Chart';
import { getWidgetOptions, setWidgetOptions } from './options';

const Widget = () => {
	const [ isLoading, setIsLoading ] = useState( true ),
		[ timeframe, setTimeframe ] = useState(),
		[ options, setOptions ] = useState( {} );

	const onTimeframeChange = async ( newTimeframe ) => {
		const resp = await setWidgetOptions( { timeframe: newTimeframe } );
		if ( resp.success ) {
			setTimeframe( newTimeframe );
		}
	};

	useEffect( async () => {
		const widgetOptions = await getWidgetOptions();
		setOptions( widgetOptions );
		setTimeframe( widgetOptions.timeframe );
		setIsLoading( false );
	}, [] );

	if ( isLoading ) {
		return <>{ __( 'Loading…', 'dbdw' ) }</>;
	}

	return (
		<Layout>
			<Header
				title={ __( 'Graph Widget', 'dbdw' ) }
				navigation={
					<TimeframeSelect
						onTimeframeChange={ onTimeframeChange }
						timeframe={ timeframe }
					/>
				}
			/>
			<Chart
				timeframe={ timeframe }
				data={ options.charts || [] }
				chartLines={ options.chart_lines || [] }
			/>
		</Layout>
	);
};

export default Widget;
