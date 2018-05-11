import React, { Component } from 'react';
import { connect } from 'react-redux';

import Backdrop from '../UI/Backdrop/Backdrop';
import Hoc from '../../hoc/Hoc/Hoc';

 class Loader extends Component {
  render() {
      return (
        <Hoc>
          <Backdrop show="true" />
          <div className="body">
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
          <div className="longfazers">
            <span />
            <span />
            <span />
            <span />
          </div>
          <h1 className="loader-title">{this.props.loaderTitle}</h1>
        </Hoc>
      );
  }  
}

const mapStateToProps = state => {
  return {
    loadingProviders: state.providers.loadingProviders,
    loadingGuidebotData: state.guidebot.loadingGuidebotData,
  }
}

export default connect(mapStateToProps)(Loader);
