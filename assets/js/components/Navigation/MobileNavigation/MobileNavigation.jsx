import React from 'react';

const mobileNavigation = (props) => {
    return (
        <div className="mobile-navigation">
            <button className="mobile-navigation__button mobile-navigation__back" onClick={props.clickedBack} >{props.back}</button>
            <button className="mobile-navigation__button mobile-navigation__next" onClick={props.clickedNext} >{props.next}</button>
        </div>
    );
}

export default mobileNavigation;