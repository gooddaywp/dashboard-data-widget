import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';

const TimeframeSelect = ( { timeframe, onTimeframeChange } ) => (
	<SelectControl
		className="dbdw-timeframe-select"
		onChange={ ( val ) => onTimeframeChange( val ) }
		value={ timeframe }
		options={ [
			{
				label: __( '7 Days', 'dbdw' ),
				value: '7-days',
			},
			{
				label: __( '15 Days', 'dbdw' ),
				value: '15-days',
			},
			{
				label: __( '1 Month', 'dbdw' ),
				value: '1-months',
			},
		] }
	/>
);

export default TimeframeSelect;
