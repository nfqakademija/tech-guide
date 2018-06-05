import React, { Component } from 'react';
import { connect } from 'react-redux';
import { ThemeProvider } from 'styled-components';
import { isMobile } from "react-device-detect";
import axios from 'axios';
import ChatBot from 'react-simple-chatbot';

import Hoc from '../../hoc/Hoc/Hoc';
import * as guidebotActionCreators from '../../store/actions/guidebot';
import * as providersActionCreators from '../../store/actions/providers';
import * as navigationActionCreators from '../../store/actions/navigation';

class Quiz extends Component {

  handleEnd = ( values ) => {
    this.props.onGetResults(values);
    if ( isMobile ) {
      this.props.onSetCurrentPage(3);
    } else {
      this.props.onSetCurrentPage(2);
    }
  }

  render() {
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
          steps={this.props.messages}
          width="100%"
          botDelay="100"
          hideSubmitButton="true"
          handleEnd={( values ) => this.handleEnd(values)}
          className="rsc-root"
          botAvatar= "images/chatbot-icon.svg"
          hideUserAvatar= "true"
        />
      </ThemeProvider>
    </Hoc>
    );
  }
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
    onFetchGuidebotData: () => dispatch(guidebotActionCreators.fetchGuidebotData()),
    onProvidersHistorySet: ( cookies ) => dispatch(providersActionCreators.providersHistorySet( cookies )),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(Quiz);
