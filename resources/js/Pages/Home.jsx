import React from 'react';
import PropTypes from 'prop-types';

Home.propTypes = {
    name: PropTypes.string
};

function Home(props) {
    return (
        <div>
			<h1>Hello, {props.name}</h1>
        </div>
    );
}

export default Home;
