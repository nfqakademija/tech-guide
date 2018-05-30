import React from 'react';

import Backdrop from '../UI/Backdrop/Backdrop';
import Hoc from '../../hoc/Hoc/Hoc';

  const loader = (props) => (
    <Hoc>
      <Backdrop show="true" style={props.style} />
      <div className="body" style={props.style}>
        <span>
        <span />
        <span />
        <span />
        <span />
        </span>
        <div className="base">
          <span />
          <div className="face" />
        </div>
      </div>
      <div className="longfazers" style={props.style} >
        <span />
        <span />
        <span />
        <span />
      </div>
      <h1 style={props.style} className="loader-title" >{props.loaderTitle}</h1>
    </Hoc>
);

export default loader;
