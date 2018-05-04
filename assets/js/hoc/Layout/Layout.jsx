import React, { Component } from 'react';
import { connect } from 'react-redux';
import axios from 'axios';

import Navigation from '../../components/Navigation/Navigation';
import SideDrawer from '../../components/SideDrawer/SideDrawer';
import Hoc from '../Hoc/Hoc.jsx';
import Quiz from '../../containers/Quiz/Quiz';
import Home from '../../containers/Home/Home';
import Loader from '../../components/Loader/Loader';
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

    if (this.props.guidebotDataSet) {
      return (
        <Hoc>
          <Loader loaderTitle="GUIDEBOT - BEST TECHNOLOGY ADVISOR SINCE 1950s" />
          <div className={`card ${attachedClasses.join(' ')}`}>
            <Home clicked={this.props.onShowGuidebot} />
            <Quiz messages={this.props.messages} quizStarted={this.props.showGuidebot} />
          </div>
        </Hoc>
      );
    } else {
        return (
          <Hoc>
            <Loader loaderTitle="GUIDEBOT - BEST TECHNOLOGY ADVISOR SINCE 1950s" />
            <div className={`card ${attachedClasses.join(' ')}`}>
              <Home clicked={this.props.onShowGuidebot} />
            </div>
          </Hoc>
        );    
    }
  }
}

const mapStateToProps = state => {
  return {
    messages: state.guidebot.messages,
    loadingGuidebotData: state.guidebot.loadingGuidebotData,
    guidebotDataSet: state.guidebot.guidebotDataSet,
    showGuidebot: state.guidebot.showGuidebot,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onFetchGuidebotData: () => dispatch(actionCreators.fetchGuidebotData()),
    onShowGuidebot: () => dispatch(actionCreators.showGuidebot()),
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Layout);
