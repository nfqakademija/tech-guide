import React from 'react';
import { connect } from 'react-redux';

import Hoc from '../Hoc/Hoc.jsx';
import Modal from '../../components/UI/Modal/Modal';
import SavedQuizes from '../../containers/SavedQuizes/SavedQuizes';
import Quiz from '../../containers/Quiz/Quiz';
import Home from '../../containers/Home/Home';
import Loader from '../../components/Loader/Loader';
import Results from '../../components/Providers/Results/Results';
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
        <Hoc>
          <div className="row desktop-main">
            <div className={`card ${attachedClasses.join(' ')}`}>
              <Home />
              <div className={`card__side card__side--back ${otherClasses.join('')}`}>
                {props.guidebotDataSet && props.showGuidebot ? 
                <Hoc>
                  <Results />
                  <Quiz /> 
                </Hoc> : null}
              </div>
            </div>
          </div>
          <Modal>
            <SavedQuizes cookies={props.cookies} />
          </Modal>
        </Hoc>
      );
  }

const mapStateToProps = state => {
  return {
    loadingGuidebotData: state.guidebot.loadingGuidebotData,
    guidebotDataSet: state.guidebot.guidebotDataSet,
    showGuidebot: state.guidebot.showGuidebot,
    loadingProviders: state.providers.loadingProviders,
    providersSet: state.providers.providersSet,
  }
}

export default connect(mapStateToProps, null)(layout);
