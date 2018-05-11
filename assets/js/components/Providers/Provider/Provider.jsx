import React from 'react';

const provider = (props) => {

  return (
    <li className="results__provider">
      <a href={props.link} target="_blank">
        <img className="provider__img" src="http://laisvespiknikas.lt/wp-content/uploads/2017/08/topo.png" />
      </a>
      <a className="provider__button" href={props.link} target="_blank">Į parduotuvę</a>
    </li>
  );
}

export default provider;
