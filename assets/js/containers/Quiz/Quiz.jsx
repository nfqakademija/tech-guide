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
          url: "",
          providersSet: false,
          loadingProviders: false
      };
    }

    componentDidMount() {
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
        botBubbleColor: '#f1f0f0',
        botFontColor: '#4b4f56',
        userBubbleColor: 'rgb(40, 180, 133)',
        userFontColor: 'white',
      }

      const inputStyle = {
        display: 'none'
      }

      return (
        <Hoc>
          <Providers
            loadingProviders={this.state.loadingProviders}
            show={this.state.providersSet}
            link={this.state.url}
          />
          <ThemeProvider theme={theme}>
            <ChatBot
              hideHeader="true"
              steps={this.props.messages}
              width="100%"
              botDelay="150"
              hideSubmitButton="true"
              inputStyle={inputStyle}
              handleEnd={this.handleEnd}
              className="rsc-root"
            />
          </ThemeProvider>
        </Hoc>
      );
    }
}

export default Quiz;
