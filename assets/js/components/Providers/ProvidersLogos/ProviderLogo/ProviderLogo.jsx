import React from 'react';

const providerLogo = (props) => (
    <div className="provider__logo">
        <a href={props.link} target="_blank" >
            <img src={props.src} />
        </a>
    </div>
);

export default providerLogo;