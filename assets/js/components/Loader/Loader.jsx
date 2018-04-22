import React from 'react';

import Hoc from '../../hoc/Hoc/Hoc.jsx';

const loader = () => {
  return (
  <Hoc>
      <div class="body">
        <span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </span>
          <div class="base">
            <span></span>
            <div class="face"></div>
          </div>
      </div>
      <div class="longfazers">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
      <h1 class="loader-title">Guidebot is coming...</h1>
  </Hoc>
  );
}

export default loader;
