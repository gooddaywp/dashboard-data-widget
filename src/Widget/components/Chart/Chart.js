import {
	ResponsiveContainer,
	LineChart,
	Line,
	CartesianGrid,
	XAxis,
	YAxis,
	Tooltip,
} from 'recharts';

const Chart = ( { timeframe, data, chartLines } ) => {
	switch ( timeframe ) {
		case '15-days':
			timeframe = 15;
			break;
		case '1-months':
			timeframe = 30;
			break;
		case '7-days':
		default:
			timeframe = 7;
			break;
	}
	data = data.slice( 0, timeframe );
	return (
		<div className="dbdw-chart">
			<ResponsiveContainer width="100%" height="100%">
				<LineChart data={ data } margin={ { right: 40 } }>
					{ chartLines.map( ( line, index ) => (
						<Line
							key={ index }
							type="monotone"
							dataKey={ line.title }
							stroke={ line.stroke }
						/>
					) ) }
					<CartesianGrid stroke="#ddd" />
					<XAxis dataKey="name" />
					<YAxis />
					<Tooltip />
				</LineChart>
			</ResponsiveContainer>
		</div>
	);
};

export default Chart;
