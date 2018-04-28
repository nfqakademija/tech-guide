import React, { Component } from 'react';
import axios from 'axios';

import Loader from '../../components/Loader/Loader.jsx';
import Layout from '../../hoc/Layout/Layout.jsx';
import ChatBot from 'react-simple-chatbot';
import { ThemeProvider } from 'styled-components';

class Quiz extends Component {
    constructor(props) {
      super(props);
      this.state = {
          messages: null,
      };
    }

    componentDidMount () {
        axios.get( '/guidebotSentences' )
            .then( response => {
                const messages = [];

                for (let i = 0; i < response.data.messages.greeting.length; i++) {
                  messages.push(response.data.messages.greeting[i]);
                }

                for (let i = 0; i < response.data.messages.questions.length; i++) {
                  messages.push(response.data.messages.questions[i]);
                }

                for (let i = 0; i < response.data.messages.options.length; i++) {
                  messages.push(response.data.messages.options[i]);
                }

                this.setState({ messages: messages });
            });

        this.handleEnd = this.handleEnd.bind(this);
    }

    handleEnd ({steps, values}) {
        axios.post('/guidebotAnswers', {
            data: [40, 1, 3, 2, 2]
        })
            .then(function (response) {
                console.log(response);
            })
    }


    render() {

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
        let message = <Loader />;
        if (this.state.messages) {
            message =(
            <ThemeProvider theme={theme}>
              <ChatBot
                headerTitle="Guidebot"
                steps={this.state.messages}
                width="100%"
                hideHeader="true"
                botDelay="100"
                inputStyle={inputStyle}
                hideSubmitButton="true"
                handleEnd={this.handleEnd}
              />
            </ThemeProvider>);
        }
        return message;
    }
}

export default Quiz;
