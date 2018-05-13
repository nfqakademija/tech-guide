import React from 'react';

const provider = (props) => {

  return (
    <li className="results__provider">
      <div className="results__provider--logo">
        <a href={props.link} target="_blank">
          <img className="provider__img" src={props.logo} />
        </a>
      </div>
      <div class="set-size charts-container">
        <div class="pie-wrapper progress-bar">
          <span class="label">{props.efficiency}<span class="smaller">%</span></span>
          <div class="pie" style={props.progressBarPie}>
            <div class="left-side half-circle" style={props.progressBarLeftSide}></div>
            <div class="right-side half-circle" style={props.progressBarRightSide}></div>
          </div>
        </div>
      </div>
      <a className="provider__button" href={props.link} target="_blank">Į parduotuvę {props.count}</a>
    </li>
  );
}

export default provider;
