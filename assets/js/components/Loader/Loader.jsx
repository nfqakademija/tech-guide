import React from 'react';
import { connect } from 'react-redux';

import Backdrop from '../UI/Backdrop/Backdrop';
import Hoc from '../../hoc/Hoc/Hoc';
import * as actionCreators from '../../store/actions/guidebot';

  const loader = (props) => {

    let loaderTitle;
    if (props.loadingProviders) {
      loaderTitle = 'PREPARING OFFERS';
    } else if (props.loadingGuidebotData) {
      loaderTitle = 'LOADING GUIDEBOT DATA';
    }

    return (
        <Hoc>
          <Backdrop show="true" />
          <div className="body" style={props.style}>
            <span>
              <span />
              <span />
              <span />
              <span />
            </span>
            <div className="base">
              <span />
              <div className="face" />
            </div>
          </div>
          <div className="longfazers" style={props.style} >
            <span />
            <span />
            <span />
            <span />
          </div>
          <h1 style={props.style} className="loader-title" >{loaderTitle}</h1>
        </Hoc>
    );
}

const mapStateToProps = state => {
  return {
    loadingProviders: state.providers.loadingProviders,
    loadingGuidebotData: state.guidebot.loadingGuidebotData,
  }
}

export default connect(mapStateToProps, null)(loader);
