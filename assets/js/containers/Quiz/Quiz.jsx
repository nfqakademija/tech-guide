import React, { Component } from 'react';
import { connect } from 'react-redux';

import ChatBot from 'react-simple-chatbot';
import { ThemeProvider } from 'styled-components';
import Hoc from '../../hoc/Hoc/Hoc';
import Providers from '../../components/Providers/Providers';
import * as actionCreators from '../../store/actions/providers';
import { isMobile } from "react-device-detect";

class Quiz extends Component {
  render() {
    const theme = {
      botBubbleColor: '#f1f0f0',
      botFontColor: '#4b4f56',
      userBubbleColor: 'rgb(40, 180, 133)',
      userFontColor: 'white',
    }

    return (
      <Hoc>
      { this.props.show == true ?  <Hoc> <Providers
            loadingProviders={this.props.loadingProviders}
            show={this.props.providersSet}
          />
          <ThemeProvider theme={theme}>
            <ChatBot
              hideHeader="true"
              steps={this.props.messages}
              width="100%"
              botDelay="1000"
              hideSubmitButton="true"
              handleEnd={this.props.onGetResults}
              className="rsc-root"
            />
          </ThemeProvider></Hoc> : null}
      </Hoc>
    );
  }
}

const mapStateToProps = state => {
  return {
    messages: state.guidebot.messages,
    providersInfo: state.providers.providersInfo,
    providersSet: state.providers.providersSet,
    loadingProviders: state.providers.loadingProviders,
    error: state.providers.error,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onGetResults: ({ values }) => dispatch(actionCreators.fetchProviders( values )),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(Quiz);
