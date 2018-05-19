import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../../../hoc/Hoc/Hoc';
import Provider from '../Provider/Provider';
import Slider from 'react-slick';
import { isMobile } from "react-device-detect";

class Results extends Component {
  constructor(props) {
    super(props);
    this.state = {
        resultsOpened: true
    };
  }

  resultsToggleHandler = ( prevState ) => {
    this.setState( ( prevState ) => {
      return { resultsOpened: !prevState.resultsOpened }
    });
  }

  render() {
      const generatedProviders = Object.keys( this.props.providersInfo )
                                    .map( providerKey => {
                                      let count;
                                      if (this.props.providersInfo[providerKey].count != -1) {
                                        count = `(${this.props.providersInfo[providerKey].count})`;
                                      }

                                      let progressBarRightSide;
                                      let progressBarLeftSide;
                                      let progressBarPie;
                                      if (this.props.providersInfo[providerKey].filterUsage <= 50) {
                                        progressBarRightSide = {
                                          transform: `rotate(${this.props.providersInfo[providerKey].filterUsage/100*360}deg)`,
                                        },
                                        progressBarLeftSide = {
                                          display: "none",
                                        }
                                      } else {
                                        progressBarRightSide = {
                                          transform: "rotate(180deg)",
                                        }
                                        progressBarLeftSide = {
                                          transform: `rotate(${this.props.providersInfo[providerKey].filterUsage/100*360}deg)`,
                                        }
                                        progressBarPie = {
                                          clip: "rect(auto, auto, auto, auto)",
                                        }
                                      }

                                      return (
                                        <Provider 
                                          key={providerKey} 
                                          link={this.props.providersInfo[providerKey].url} 
                                          logo={this.props.providersInfo[providerKey].logo} 
                                          count={count} 
                                          efficiency={this.props.providersInfo[providerKey].filterUsage} 
                                          progressBarLeftSide={progressBarLeftSide} 
                                          progressBarRightSide={progressBarRightSide} 
                                          progressBarPie={progressBarPie} />
                                      );
                                    });

    if (this.props.providersSet) {
      return (
        <div className="results">
            <div className="results__providers">
              {generatedProviders}
            </div>
            <a onClick={this.resultsToggleHandler} href="#"><img className="results__exit" src="images/close-button.svg" /></a>
        </div>
      );
    } else {
      return null;
    }

  }
}

const mapStateToProps = state => {
  return {
    providersInfo: state.providers.providersInfo,
    providersSet: state.providers.providersSet,
  }
}


export default connect(mapStateToProps)(Results);
