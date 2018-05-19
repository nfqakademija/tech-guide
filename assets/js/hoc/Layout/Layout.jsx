import React from 'react';
import { connect } from 'react-redux';

import Navigation from '../../components/Navigation/Navigation';
import Hoc from '../Hoc/Hoc.jsx';
import Quiz from '../../containers/Quiz/Quiz';
import Home from '../../containers/Home/Home';
import Loader from '../../components/Loader/Loader';
import ProvidersLogos from '../../components/Providers/ProvidersLogos/ProvidersLogos';
import * as actionCreators from '../../store/actions/guidebot';
import * as actionCreatorsProviders from '../../store/actions/providers';


const layout = (props) => {

    let attachedClasses = [];
    let otherClasses = [];
    if (props.showGuidebot) {
        attachedClasses = ["quiz-started"];
        otherClasses = ["priority"];
    }
   
      return (
        <div className="row main">
          <div className={`card ${attachedClasses.join(' ')}`}>
            <Home />
            <div className={`card__side card__side--back ${otherClasses.join('')}`}>
              {props.guidebotDataSet && props.showGuidebot ? <Quiz show="true" quizStarted={props.showGuidebot} /> : null }
            </div>
          </div>
          <ProvidersLogos />
        </div>
      );
  }

const mapStateToProps = state => {
  return {
    loadingGuidebotData: state.guidebot.loadingGuidebotData,
    guidebotDataSet: state.guidebot.guidebotDataSet,
    showGuidebot: state.guidebot.showGuidebot,
    loadingProviders: state.providers.loadingProviders,
  }
}

export default connect(mapStateToProps, null)(layout);
