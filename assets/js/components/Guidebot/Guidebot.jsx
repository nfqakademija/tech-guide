import React, { Component } from 'react';
import { ThemeProvider } from 'styled-components';
import ChatBot from 'react-simple-chatbot';
import data from '../../data.json';

class Guidebot extends Component {
  constructor(props) {
    super(props);
    this.state = {
        data: ''
    };
  }

  componentWillMount () {
    const min = 0;
    const max = 3;
    const messages = [];

    for (let i = 0; i < data.messages.greeting.length; i++) {
      messages.push(data.messages.greeting[i]);
    }

    for (let i = 0; i < data.messages.questions.length; i++) {
      messages.push(data.messages.questions[i]);
    }

    for (let i = 0; i < data.messages.options.length; i++) {
      messages.push(data.messages.options[i]);
    }
    this.setState({ data: messages });

  }

  render () {
    const theme = {
      background: '#f5f8fb',
      fontFamily: 'Helvetica Neue',
      botBubbleColor: 'white',
      botFontColor: 'black',
      userBubbleColor: '#fff',
      userFontColor: '#4a4a4a',
    }

    const inputStyle = {
      display: 'none'
    }

    return (
        <ThemeProvider theme={theme}>
            <ChatBot
              headerTitle="Guidebot"
              steps={this.state.data}
              width="100%"
              hideHeader="true"
              botDelay="1500"
              hideUserAvatar="true"
              inputStyle={inputStyle}
              hideSubmitButton="true"
            />
        </ThemeProvider>
    );
  }
}

export default Guidebot;
