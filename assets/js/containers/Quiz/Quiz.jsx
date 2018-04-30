import React, { Component } from 'react';
import axios from 'axios';

import Loader from '../../components/Loader/Loader.jsx';
import Layout from '../../hoc/Layout/Layout.jsx';
import ChatBot from 'react-simple-chatbot';
import { ThemeProvider } from 'styled-components';
import Hoc from '../../hoc/Hoc/Hoc';
import Providers from '../../components/Providers/Providers';
import Backdrop from '../../components/UI/Backdrop/Backdrop';
import Gif from '../../components/Gif/Gif';

class Quiz extends Component {
    constructor(props) {
      super(props);
      this.state = {
          messages: [],
          url: "",
          providersSet: false,
          loadingProviders: false
      };
    }

    componentDidMount () {
        axios.get( '/api/guidebotSentences' )
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
        let currentComponent = this;
        this.setState({ loadingProviders: true });
        axios.post('/api/guidebotOffer', {
            data: values
        })
            .then(function (response) {
                // console.log(response.data[0]);
                const url = response.data[0];
                currentComponent.setState({ url: url, providersSet: true, loadingProviders: false });
            })
    }


    render() {

      const theme = {
        botBubbleColor: 'white',
        botFontColor: '#777',
        userBubbleColor: 'rgb(40, 180, 133)',
        userFontColor: 'white',
      }

      const inputStyle = {
        display: 'none'
      }

      if (this.state.messages.length > 0) {
            return (
              <Hoc>
                <Providers
                  loadingProviders={this.state.loadingProviders}
                  show={this.state.providersSet}
                  link={this.state.url} />
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
                    className="rsc-root"
                  />
                </ThemeProvider>
              </Hoc>
            );
        } else {
            return <Loader guideboteTitle="GUIDEBOT - BEST TECH ADVISOR SINCE 1950s" />
        }
    }
}

export default Quiz;
