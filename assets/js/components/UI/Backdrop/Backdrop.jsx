import React from 'react';

const backdrop = (props) => (
    <div style={props.style} onClick={props.clicked} className="backdrop" />
);

export default backdrop;
