import React from 'react';

import Hoc from '../../hoc/Hoc/Hoc';
import Button from '../../components/UI/Button/Button';

const home = () => {
  return (
    <Hoc>
      <div className="row main">
        <div className="col-6 main-box">
          <div className="main-box__text">
              <h1 className="main-box__text--title">Techguide</h1>
              <p className="main-box__text--regular">Helps when every device looks the same.</p>
          </div>
        </div>
      </div>
    </Hoc>
  );
}

export default home;
