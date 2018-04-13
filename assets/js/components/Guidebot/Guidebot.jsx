import React, { Component } from 'react';

import ChatBot from 'react-simple-chatbot';
import data from '../../demodata.json';

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

    const randomNumber = Math.floor(min + Math.random() * (max - min));
    const randomGreeting = data.messages.greeting[0].message[randomNumber];
    data.messages.greeting[0].message = randomGreeting;
    messages.push(data.messages.greeting[0]);


    for (var i = 0; i < data.messages.questions.length; i++) {
      messages.push(data.messages.questions[i]);
    }

    for (var i = 0; i < data.messages.options.length; i++) {
      messages.push(data.messages.options[i]);
    }
    this.setState({ data: messages });

  }

  render () {
    const randomGreeting = this.state.randomGreeting;
    return (
      <ChatBot
        headerTitle="Guidebot"
        handleEnd={this.handleEnd}
        steps={this.state.data}
      />
    );
  }
}

export default Guidebot;
