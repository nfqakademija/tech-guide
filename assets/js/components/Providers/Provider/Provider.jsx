import React from 'react';

const provider = (props) => {

  return (
    <li className="results__provider">
      <div className="results__provider--logo">
        <a href={props.link} target="_blank">
          <img className="provider__img" src={props.logo} />
        </a>
      </div>
      <div className="set-size charts-container">
        <div className="pie-wrapper progress-bar">
          <span className="label">{props.efficiency}<span className="smaller">%</span></span>
          <div className="pie" style={props.progressBarPie}>
            <div className="left-side half-circle" style={props.progressBarLeftSide}></div>
            <div className="right-side half-circle" style={props.progressBarRightSide}></div>
          </div>
        </div>
        <dfn data-info="Progress bar shows the percentage of your given answers that were used to generate offers just for you.">
          <img className="progress-bar__info" src="images/information.svg" />
        </dfn>
      </div>
      <a className="provider__button" href={props.link} target="_blank">Į parduotuvę {props.count}</a>
    </li>
  );
}

export default provider;
