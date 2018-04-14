import React from 'react';

import Button from '../UI/Button/Button.jsx';

const navigation = () => {
  return (
    <nav className="navbar navbar-expand-lg navbar-light">
      <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span className="navbar-toggler-icon"></span>
      </button>

      <div className="collapse navbar-collapse" id="navbarSupportedContent">
        <ul className="navbar-nav ml-auto">
          <li className="nav-item">
            <Button actionName="Home" link="/" />
          </li>
          <li className="nav-item">
            <Button actionName="Log in" />
          </li>
          <li className="nav-item">
            <Button actionName="Sign up" buttonType="signup-button" />
          </li>
        </ul>
      </div>
    </nav>
  );
}

export default navigation;
