import React, { Component } from 'react';
import { connect } from 'react-redux';
import { isMobile } from "react-device-detect";
import axios from 'axios';
import Transition from 'react-transition-group/Transition';

import Hoc from './hoc/Hoc/Hoc';
import Layout from './hoc/Layout/Layout';
import MobileLayout from './hoc/MobileLayout/MobileLayout';
import Loader from './components/Loader/Loader';
import * as guidebotActionCreators from './store/actions/guidebot';
import * as providersActionCreators from './store/actions/providers';

class App extends Component {
  constructor(props) {
    super(props);
    this.state = {
      cookies: [],
    }
    this.updateDimensions = this.updateDimensions.bind(this);
  }

  updateDimensions() {
      document.body.style.height = window.innerHeight + 'px';
  }

  componentWillMount() {
      document.body.style.height = window.innerHeight + 'px';
  }

  componentDidUpdate() {
    document.body.style.height = window.innerHeight + 'px';
  }

  componentWillUnmount() {
      window.removeEventListener("resize", this.updateDimensions );
  }

  componentDidMount() {
    this.props.onFetchGuidebotData();
    window.addEventListener("resize", this.updateDimensions );

    let cookies = this.getCookie('answers');
    if (cookies === null) {
      return this.props.onProvidersHistorySet();
    }
    let parsedCookies = JSON.parse(cookies);

    let arrayOfCookies = [];
    let promises = [];
    let generateCookies = Object.keys( parsedCookies )
      .map( cookieKey => {
        promises.push(axios.get(`/answers/get/${parsedCookies[cookieKey].id}`))
    })
    let storedCookies = [];
    axios.all(promises).then(function(results) {
      results.forEach(function(response) {
        if (typeof response.data == 'object' && response.data.constructor === Object) {
          storedCookies.push(response.data);
        }
      })
    }).then(() => {
      this.setState({ cookies: storedCookies });
      this.props.onProvidersHistorySet();
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

  render() {

    let loaderTitle;
    if (this.props.loadingGuidebotData || (this.props.showLoader && this.props.guidebotDataSet) || !this.props.providersHistorySet ) {
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
            <Loader 
              style={{
                ...defaultStyle,
                ...transitionStyles[state]
              }} 
              loaderTitle={loaderTitle}
            />
          )}
        </Transition>
        { isMobile ? <MobileLayout cookies={this.state.cookies}/> : <Layout cookies={this.state.cookies} /> }
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
