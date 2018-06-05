import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../Hoc/Hoc.jsx';
import Guidebot from '../../containers/Guidebot/Guidebot';
import Home from '../../containers/Home/Home';

const layout = (props) => {

  let attachedClasses = [];
  if (props.showGuidebot) {
      attachedClasses = ["quiz-started"];
  }

  return (
    <Hoc>
      <div className="row desktop-main">
        <div className={`card ${attachedClasses.join(' ')}`}>
          <Home />
          <Guidebot />
        </div>
      </div>
    </Hoc>
  );
}

const mapStateToProps = state => {
  return {
    showGuidebot: state.guidebot.showGuidebot,
  }
}

export default connect(mapStateToProps)(layout);
