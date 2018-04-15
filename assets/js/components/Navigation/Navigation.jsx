import React from 'react';

import Button from '../UI/Button/Button.jsx';

const navigation = () => {
  return (
    <nav className="navbar navbar-light">
      <div className="button-wrapper ml-auto">
            <Button actionName="Home" link="/" />
            <Button actionName="Log in" link="#" />
            <Button actionName="Sign up" link="#" buttonType="signup-button" />
      </div>
    </nav>
  );
}

export default navigation;
