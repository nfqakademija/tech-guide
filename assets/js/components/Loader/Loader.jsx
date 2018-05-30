import React from 'react';

import Backdrop from '../UI/Backdrop/Backdrop';
import Hoc from '../../hoc/Hoc/Hoc';

  const loader = (props) => (
    <Hoc>
      <Backdrop style={props.style}/>
      <div className="loader" style={props.style}>
        <div className="loader__inner" style={props.style}>
            <div className="loader__line-wrap">
                <div className="loader__line"></div>
            </div>
            <div className="loader__line-wrap">
                <div className="loader__line"></div>
            </div>
            <div className="loader__line-wrap">
                <div className="loader__line"></div>
            </div>
            <div className="loader__line-wrap">
                <div className="loader__line"></div>
            </div>
            <div className="loader__line-wrap">
                <div className="loader__line"></div>
            </div>
        </div>
        <div className="loader__title">{props.loaderTitle}</div>
      </div>
    </Hoc>
);

export default loader;
