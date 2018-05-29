import React from 'react';
import { connect } from 'react-redux';
import { isMobile } from "react-device-detect";

import Hoc from '../../hoc/Hoc/Hoc';
import ProvidersLogos from '../../components/Providers/ProvidersLogos/ProvidersLogos';
import * as guidebotActionCreators from '../../store/actions/guidebot';
import * as navigationActionCreators from '../../store/actions/navigation'

const home = (props) => {

  const showGuidebot = (index) => {
    props.onShowGuidebot();
    props.onSetCurrentPage(index)
  }
  
  return (
    <Hoc>
    { isMobile ? 
    <Hoc>
      <div className="mobile-landing__image">
        <img className="main-image" src="images/question-mobile1.svg" alt="make decision" />
      </div>
      <div className="mobile-landing__text-box">
        <h1 className="heading-primary">
          <span className="heading-primary__main">Techguide</span>
          <span className="heading-primary__sub">Helps when every device looks the same</span>
        </h1>
      </div>
    </Hoc>
    :
    <div className="card__side  card__side--front">
      <div className="row card__content-wrapper">
        <div className="col-home col--text">
          <div className="card__text-box">
            <h1 className="heading-primary">
              <span className="heading-primary__main">TECHGUIDE</span>
              <span className="heading-primary__sub">HELPS WHEN EVERY DEVICE LOOKS THE SAME</span>
            </h1>
            <a onClick={() => showGuidebot(1)} className="main-button" href="#">Start quiz</a>
          </div>
          <ProvidersLogos />
        </div>
        <div className="col-home col--picture">
          
        </div>
      </div>
    </div>
    }
    </Hoc>
  );
}

const mapDispatchToProps = dispatch => {
  return {
    onSetCurrentPage: ( index ) => dispatch(navigationActionCreators.setCurrentPage( index )),
    onShowGuidebot: () => dispatch(guidebotActionCreators.showGuidebot()),
  };
}

export default connect(null, mapDispatchToProps)(home);
