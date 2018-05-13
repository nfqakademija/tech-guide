import React from 'react';
import { connect } from 'react-redux';

import Provider from '../Provider/Provider';

const results = (props) => {

  console.log(props.providersInfo);

  const generatedProviders = Object.keys( props.providersInfo )
            .map( providerKey => {
              let count;
              console.log(props.providersInfo[providerKey].count);
              if (props.providersInfo[providerKey].count != "Unknown") {
                count = `(${props.providersInfo[providerKey].count})`;
              }
              return (
                <Provider key={providerKey} link={props.providersInfo[providerKey].url} logo={props.providersInfo[providerKey].logo} count={count} />
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
    providersInfo: state.providers.providersInfo,
  }
}


export default connect(mapStateToProps)(results);
