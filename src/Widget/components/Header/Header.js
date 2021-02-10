const Header = ( { title, navigation } ) => {
	return (
		<div className="dbdw-header">
			<div className="dbdw-header-column">
				<strong className="dbdw-header-title">{ title }</strong>
			</div>
			<div className="dbdw-header-column">
				<nav className="dbdw-header-nav">{ navigation }</nav>
			</div>
		</div>
	);
};

export default Header;
