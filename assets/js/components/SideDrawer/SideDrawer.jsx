import React from 'react';
import { Link } from 'react-router-dom';
import Backdrop from '../UI/Backdrop/Backdrop';
import Hoc from '../../hoc/Hoc/Hoc';

const sideDrawer = (props) => {

  return (
      <Hoc>
              <nav className="nav__cont">
                  <ul className="nav">
                    <li className="nav__items ">
                      <img className="navigation-icon" src="images/home.svg" />
                      <Link to="/">Home</Link>
                    </li>
                    <li className="nav__items ">
                      <img className="navigation-icon" src="images/discuss-issue.svg" />
                      <Link to="/guidebot">Guidebot</Link>
                    </li>
                  </ul>
              </nav>
      </Hoc>
  );
}

export default sideDrawer;
