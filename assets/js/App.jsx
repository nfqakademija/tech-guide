import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from './hoc/Hoc/Hoc';
import Layout from './hoc/Layout/Layout';
import MobileLayout from './hoc/MobileLayout/MobileLayout';
import Loader from './components/Loader/Loader';
import { isMobile } from "react-device-detect";
import * as actionCreators from './store/actions/guidebot';

class App extends Component {

  componentDidMount() {
    this.props.onFetchGuidebotData();
  }

  render() {
    return (
      <Hoc>
        {
        !this.props.guidebotDataSet ? <Loader loaderTitle="LOADING GUIDEBOT DATA..." />
        : this.props.loadingProviders ? <Loader loaderTitle="PREPARING RESULTS..." /> 
        : null
        }
        { isMobile ? <MobileLayout /> : <Layout /> }
      </Hoc>
    );
  }
}

const mapStateToProps = state => {
  return {
    guidebotDataSet: state.guidebot.guidebotDataSet,
    loadingProviders: state.providers.loadingProviders,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onFetchGuidebotData: () => dispatch(actionCreators.fetchGuidebotData()),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
