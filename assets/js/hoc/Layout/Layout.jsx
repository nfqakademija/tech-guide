import React, { Component } from 'react';
import axios from 'axios';

import Navigation from '../../components/Navigation/Navigation';
import SideDrawer from '../../components/SideDrawer/SideDrawer';
import Hoc from '../Hoc/Hoc.jsx';
import Quiz from '../../containers/Quiz/Quiz';
import Home from '../../containers/Home/Home';
import Loader from '../../components/Loader/Loader';


class Layout extends Component {
  constructor(props) {
    super(props);
    this.state = {
      messages: [],
      quizStarted: false
    };
  }

  componentDidMount () {
      console.log("Labas");
      axios.get( '/api/guidebotSentences' )
          .then( response => {
              console.log(response);
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
  }

  quizToggleHandler = ( prevState ) => {
    this.setState( ( prevState ) => {
      return { quizStarted: !prevState.quizStarted }
    });
  }

  render() {
    let attachedClasses = [];
    let otherClasses = [];
    if (this.state.quizStarted) {
        attachedClasses = ["quiz-started"];
        otherClasses = ["priority"]
    }

    if (this.state.messages.length > 0) {
      return (
        <Hoc>
          <div className={`card ${attachedClasses.join(' ')}`}>
            <Home onClick={this.quizToggleHandler} />
            <div className={`card__side card__side--back ${otherClasses.join('')}`}>
              <Quiz messages={this.state.messages} quizStarted={this.state.quizStarted} />
            </div>
          </div>
        </Hoc>
      );
    } else {
      return (
        <Hoc>
          <Loader loaderTitle="GUIDEBOT - BEST TECHNOLOGY ADVISOR SINCE 1950s" />
          <div className={`card ${attachedClasses.join(' ')}`}>
            <Home onClick={this.quizToggleHandler} />
          </div>
        </Hoc>
      );
    }
  }
}

export default Layout;
