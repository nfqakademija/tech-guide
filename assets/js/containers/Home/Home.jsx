import React from 'react';

import Layout from '../../hoc/Layout/Layout.jsx';
import Hoc from '../../hoc/Hoc/Hoc.jsx';
import Button from '../../components/UI/Button/Button.jsx';

const home = () => {
  return (
    <Hoc>
      <div className="row main">

        <div className="col">
          <div className="action-box">
              <h1>Know what tech suits you best</h1>
              <p className="action-box__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
              <Button actionName="Take a quiz !" buttonType="main-button" link="/guidebot"/>
          </div>
        </div>

        <div className="col photo-background">
          
        </div>

      </div>
    </Hoc>
  );
}

export default home;
