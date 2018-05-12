import React from 'react';

const provider = (props) => {

  return (
    <li className="results__provider">
      <div className="results__provider--logo">
        <a href={props.link} target="_blank">
          <img className="provider__img" src={props.logo} />
        </a>
      </div>
      <a className="provider__button" href={props.link} target="_blank">Į parduotuvę</a>
    </li>
  );
}

export default provider;
