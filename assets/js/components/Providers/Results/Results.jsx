import React from 'react';
import { connect } from 'react-redux';

import Provider from '../Provider/Provider';

const results = (props) => {

  const generatedProviders = Object.keys( props.urls )
            .map( urlKey => {
              return (
                <Provider key={urlKey} link={props.urls[urlKey]} />
              );
            });

    if (props.show) {
      return (
        <div className="results">
          <ul className="results__providers">
            {generatedProviders}
          </ul>
          <a onClick={props.onClick} href="#"><img className="results__exit" src="images/close-button.svg" /></a>
        </div>
      );
    } else {
      return null;
    }
}

const mapStateToProps = state => {
  return {
    urls: state.providers.urls,
  }
}


export default connect(mapStateToProps)(results);
