import React from 'react';

import Hoc from '../../hoc/Hoc/Hoc';
import Button from '../../components/UI/Button/Button';

const home = (props) => {
  return (
        <div className="card__side  card__side--front">
          <div className="row card__content-wrapper">
            <div className="col-sm-6">
              <div className="card__text-box">
                <h1 className="heading-primary">
                  <span className="heading-primary__main">TECHGUIDE</span>
                  <span className="heading-primary__sub">HELPS WHEN EVERY DEVICE LOOKS THE SAME</span>
                </h1>
                <a onClick={props.onClick} className="main-button" href="#">Start quiz!</a>
              </div>
            </div>
            <div className="col-sm-6">
              <img className="vector-picture" src="images/question.svg" />
            </div>
          </div>
        </div>
  );
}

export default home;
