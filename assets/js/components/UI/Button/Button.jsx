import React from 'react';

const button = (props) => {
  return (
    <a role="button" href={`${props.link}`} className={`btn button nav-button ${props.buttonType}`}>{props.actionName}</a>
  );
}

export default button;
