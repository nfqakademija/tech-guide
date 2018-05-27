import React from 'react';
import { isMobile } from "react-device-detect";

import Hoc from '../../hoc/Hoc/Hoc';

const savedQuizes = (props) => {

    let visualiseCookie = props.cookies.map( cookie => {
        let cookieInfo = cookie.map( (cookieInfo, index) => {
            return (
                <div className="providers__cookie" key={index}>CookieInfo</div>
            );
        })

        return (
            <div>{cookieInfo}</div>
        );
    })

    return (
        <Hoc>
            { isMobile ? 
                <div>Mobile cookies</div>
            : 
                <div>{visualiseCookie}</div>
            }
        </Hoc>
    );
}

export default savedQuizes;