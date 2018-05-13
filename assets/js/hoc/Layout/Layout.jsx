import React, { Component } from 'react';
import { connect } from 'react-redux';
import axios from 'axios';

import Navigation from '../../components/Navigation/Navigation';
import SideDrawer from '../../components/SideDrawer/SideDrawer';
import Hoc from '../Hoc/Hoc.jsx';
import Quiz from '../../containers/Quiz/Quiz';
import Home from '../../containers/Home/Home';
import Loader from '../../components/Loader/Loader';
import ProvidersLogos from '../../components/Providers/ProvidersLogos/ProvidersLogos';
import * as actionCreators from '../../store/actions/guidebot';
import * as actionCreatorsProviders from '../../store/actions/providers';


class Layout extends Component {

  componentDidMount () {
      this.props.onFetchGuidebotData();
  }

  render() {
    let attachedClasses = [];
    if (this.props.showGuidebot) {
        attachedClasses = ["quiz-started"];
    }

   
      return (
        <div className="row main">
          {
          !this.props.guidebotDataSet ? <Loader loaderTitle="LOADING GUIDEBOT DATA..." />
          : this.props.loadingProviders ? <Loader loaderTitle="PREPARING RESULTS..." /> 
          : null
          }
          <div className={`card ${attachedClasses.join(' ')}`}>
            <Home clicked={this.props.onShowGuidebot} />
            {this.props.guidebotDataSet && <Quiz quizStarted={this.props.showGuidebot} />}
          </div>
          <ProvidersLogos />
        </div>
      );
  }
}

const mapStateToProps = state => {
  return {
    loadingGuidebotData: state.guidebot.loadingGuidebotData,
    guidebotDataSet: state.guidebot.guidebotDataSet,
    showGuidebot: state.guidebot.showGuidebot,
    loadingProviders: state.providers.loadingProviders,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onFetchGuidebotData: () => dispatch(actionCreators.fetchGuidebotData()),
    onShowGuidebot: () => dispatch(actionCreators.showGuidebot()),
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Layout);
