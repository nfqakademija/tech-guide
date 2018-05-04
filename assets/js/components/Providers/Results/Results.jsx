import React from 'react';

import Provider from '../Provider/Provider';

const results = (props) => {
    if (props.show) {
      return (
        <div className="results">
          <ul className="results__providers">
            <Provider link={props.link} />
          </ul>
          <a onClick={props.onClick} href="#"><img className="results__exit" src="images/close-button.svg" /></a>
        </div>
      );
    } else {
      return null;
    }
}


export default results;
