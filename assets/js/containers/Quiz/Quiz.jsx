import React, { Component } from 'react';
import { connect } from 'react-redux';
import axios from 'axios';

import ChatBot from 'react-simple-chatbot';
import { ThemeProvider } from 'styled-components';
import Hoc from '../../hoc/Hoc/Hoc';
import Providers from '../../components/Providers/Providers';
import * as actionCreators from '../../store/actions/providers';

class Quiz extends Component {
  render() {
    const theme = {
      botBubbleColor: '#f1f0f0',
      botFontColor: '#4b4f56',
      userBubbleColor: 'rgb(40, 180, 133)',
      userFontColor: 'white',
    }

    let otherClasses = [];
    if (this.props.quizStarted) {
      otherClasses = ["priority"];
    }

    return (
      <div className={`card__side card__side--back ${otherClasses.join('')}`}>
        <Providers
          loadingProviders={this.props.loadingProviders}
          show={this.props.providersSet}
        />
        <ThemeProvider theme={theme}>
          <ChatBot
            hideHeader="true"
            steps={this.props.messages}
            width="100%"
            botDelay="150"
            hideSubmitButton="true"
            handleEnd={this.props.onGetResults}
            className="rsc-root"
          />
        </ThemeProvider>
      </div>
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
