import React, { Component } from 'react';

import ChatBot from 'react-simple-chatbot';
import data from '../../data.json';

class Guidebot extends Component {
  constructor(props) {
    super(props);
    this.state = {
        randomGreeting: ''
    };
  }

  componentWillMount () {
    const min = 0;
    const max = 3;
    const randomNumber = Math.floor(min + Math.random() * (max - min));
    const randomGreeting = `${data.messages.greeting[randomNumber]}`;
    this.setState({ randomGreeting: randomGreeting });
  }

  render () {
    const randomGreeting = this.state.randomGreeting;
    return (
      <ChatBot
        headerTitle="Guidebot"
        steps={[
          {
            id: '1',
            message: `${randomGreeting}`,
            end: true
          },
        ]}
      />
    );
  }
}

export default Guidebot;
