import React, { Component } from 'react';
import { connect } from 'react-redux';
import { ThemeProvider } from 'styled-components';
import { isMobile } from "react-device-detect";
import axios from 'axios';
import ChatBot from 'react-simple-chatbot';

import Hoc from '../../hoc/Hoc/Hoc';
import * as providersActionCreators from '../../store/actions/providers';
import * as navigationActionCreators from '../../store/actions/navigation';

const quiz = (props) => {

  const handleEnd = ( values ) => {
    props.onGetResults(values);
    if ( isMobile ) {
      props.onSetCurrentPage(3);
    } else {
      props.onSetCurrentPage(2);
    }
  }

  const theme = {
    botBubbleColor: '#f1f0f0',
    botFontColor: '#4b4f56',
    userBubbleColor: '#f49569',
    userFontColor: 'white',
  }

  return (
    <Hoc>
      <ThemeProvider theme={theme}>
        <ChatBot
          hideHeader="true"
          steps={props.messages}
          width="100%"
          botDelay="900"
          hideSubmitButton="true"
          handleEnd={( values ) => handleEnd(values)}
          className="rsc-root"
          botAvatar= "images/chatbot-icon.svg"
          hideUserAvatar= "true"
        />
      </ThemeProvider>
    </Hoc>
  );
}

const mapStateToProps = state => {
  return {
    messages: state.guidebot.messages,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onGetResults: ({ values }) => dispatch(providersActionCreators.fetchProviders( values )),
    onSetCurrentPage: ( index ) => dispatch(navigationActionCreators.setCurrentPage( index )),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(quiz);
