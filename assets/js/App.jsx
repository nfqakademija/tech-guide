import React, { Component } from 'react';
import axios from 'axios';
import { connect } from 'react-redux';
import Transition from 'react-transition-group/Transition';

import Hoc from './hoc/Hoc/Hoc';
import Layout from './hoc/Layout/Layout';
import MobileLayout from './hoc/MobileLayout/MobileLayout';
import Loader from './components/Loader/Loader';
import { isMobile } from "react-device-detect";
import * as guidebotActionCreators from './store/actions/guidebot';
import * as providersActionCreators from './store/actions/providers';

class App extends Component {
  constructor(props) {
    super(props);
    this.state = {
      cookies: [],
    }
  }

  componentDidMount() {
    this.props.onFetchGuidebotData();

    let cookies = this.getCookie('answers');
    if (cookies === null) {
      return this.setState({ cookies: -1 });
    }
    let parsedCookies = JSON.parse(cookies);

    let arrayOfCookies = [];
    let generateCookies = Object.keys( parsedCookies )
      .map( cookieKey => {
        axios.get(`/answers/get/${parsedCookies[cookieKey].id}`)
          .then( response => {
            if (typeof response.data[Object.keys(response.data)[0]] == 'object') {
              arrayOfCookies.push(response.data[Object.keys(response.data)[0]])
              this.setState({cookies: arrayOfCookies});
            }
          });
    })
  }

  getCookie = (name) => {
    let nameEQ = name + "=";
    let ca = decodeURIComponent(document.cookie).split(';');
    for(let i=0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
    return null;
  }

  componentDidUpdate() {
    if (this.state.cookies.length > 0 || this.state.cookies === -1) {
      this.props.onProvidersHistorySet();
    }
  }

  render() {

    let loaderTitle;
    if (this.props.loadingGuidebotData || (this.props.showLoader && this.props.guidebotDataSet) || this.state.cookies === -1 ) {
      loaderTitle = 'GUIDEBOT IS COMING...';
    } else if (this.props.loadingProviders || this.props.showLoader && this.props.providersSet) {
      loaderTitle = 'PREPARING RESULTS...'
    }

    const duration = 400;

    const defaultStyle = {
      transition: `opacity ${duration}ms ease-in-out`,
      opacity: 0,
    }

    const transitionStyles = {
      entering: { 
        opacity: 0 
      },
      entered:  { 
        opacity: 1 
      },
    };

    return (
      <Hoc>
        <Transition in={this.props.loadingGuidebotData || this.props.loadingProviders || !this.props.providersHistorySet} timeout={duration} unmountOnExit>
          {(state) => (
            <Loader style={{
              ...defaultStyle,
              ...transitionStyles[state]
            }} />
          )}
        </Transition>
        { isMobile ? <MobileLayout /> : <Layout cookies={this.state.cookies} /> }
      </Hoc>
    );
  }
}

const mapStateToProps = state => {
  return {
    guidebotDataSet: state.guidebot.guidebotDataSet,
    providersSet: state.providers.providersSet,
    loadingProviders: state.providers.loadingProviders,
    loadingGuidebotData: state.guidebot.loadingGuidebotData,
    showLoader: state.guidebot.showLoader,
    providersHistorySet: state.providers.providersHistorySet,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onFetchGuidebotData: () => dispatch(guidebotActionCreators.fetchGuidebotData()),
    onProvidersHistorySet: () => dispatch(providersActionCreators.providersHistorySet()),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
