import React from 'react';
import { connect } from 'react-redux';

import Provider from '../Provider/Provider';

const results = (props) => {

  console.log(props.providersInfo);

  const generatedProviders = Object.keys( props.providersInfo )
            .map( providerKey => {
              let count;
              if (props.providersInfo[providerKey].count != -1) {
                count = `(${props.providersInfo[providerKey].count})`;
              }
              let progressBarRightSide;
              let progressBarLeftSide;
              let progressBarPie;
              if (props.providersInfo[providerKey].filterUsage <= 50) {
                progressBarRightSide = {
                  transform: `rotate(${props.providersInfo[providerKey].filterUsage/100*360}deg)`,
                },
                progressBarLeftSide = {
                  display: "none",
                }
              } else {
                progressBarRightSide = {
                  transform: "rotate(180deg)",
                }
                progressBarLeftSide = {
                  transform: `rotate(${props.providersInfo[providerKey].filterUsage/100*360}deg)`,
                }
                progressBarPie = {
                  clip: "rect(auto, auto, auto, auto)",
                }
              }
              return (
                <Provider key={providerKey} link={props.providersInfo[providerKey].url} logo={props.providersInfo[providerKey].logo} count={count} efficiency={props.providersInfo[providerKey].filterUsage} progressBarLeftSide={progressBarLeftSide} progressBarRightSide={progressBarRightSide} progressBarPie={progressBarPie} />
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
