import React from 'react';

const home = (props) => {
  return (
    <div className="card__side  card__side--front">
      <div className="row card__content-wrapper">
        <div className="col-6 col--text">
          <div className="card__text-box">
            <h1 className="heading-primary">
              <span className="heading-primary__main">TECHGUIDE</span>
              <span className="heading-primary__sub">HELPS WHEN EVERY DEVICE LOOKS THE SAME</span>
            </h1>
            <a onClick={props.clicked} className="main-button" href="#">Start quiz!</a>
          </div>
        </div>
        <div className="col-6 col--picture">
          <img className="card__main-picture" src="images/question.svg" alt="make decision" /> 
        </div>
      </div>
    </div>
  );
}

export default home;
