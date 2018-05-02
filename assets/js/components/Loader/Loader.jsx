import React from 'react';

import Backdrop from '../UI/Backdrop/Backdrop';
import Hoc from '../../hoc/Hoc/Hoc';

const loader = (props) => {
    return (
      <Hoc>
          <Backdrop show="true" />
          <div className="body">
            <span>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </span>
              <div className="base">
                <span></span>
                <div className="face"></div>
              </div>
          </div>
          <div className="longfazers">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
          <h1 className="loader-title">{props.loaderTitle}</h1>
      </Hoc>
    );
}

export default loader;
