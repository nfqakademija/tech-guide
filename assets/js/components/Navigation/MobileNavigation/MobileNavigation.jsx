import React from 'react';

const mobileNavigation = (props) => (
    <div className="mobile-navigation" style={{height: props.style*0.1}}>
        <button className="mobile-navigation__button mobile-navigation__back" onClick={props.clickedBack} >{props.back}</button>
        <button className="mobile-navigation__button mobile-navigation__next" onClick={props.clickedNext} >{props.next}</button>
    </div>
);

export default mobileNavigation;