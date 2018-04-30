import React from 'react';

import Backdrop from '../UI/Backdrop/Backdrop';
import Hoc from '../../hoc/Hoc/Hoc';

const loaderResults = () => (
  <Hoc>
    <Backdrop show="true" />
    <div className="loader-wrapper">
    	<div className="loader">
        <div className="roller"></div>
        <div className="roller"></div>
      </div>

      <div id="loader2" className="loader">
        <div className="roller"></div>
        <div className="roller"></div>
      </div>

      <div id="loader3" className="loader">
        <div className="roller"></div>
        <div className="roller"></div>
      </div>
    </div>
    <div className="loader__text">Loading...</div>
  </Hoc>
);

export default loaderResults;
