import React, { Component } from 'react';

import Loader from '../Loader/Loader';
import Provider from './Provider/Provider';
import Hoc from '../../hoc/Hoc/Hoc';
import Backdrop from '../UI/Backdrop/Backdrop';
import Results from './Results/Results';

class Providers extends Component {
  constructor(props) {
    super(props);
    this.state = {
        resultsOpened: true
    };
  }

  resultsToggleHandler = ( prevState ) => {
    this.setState( ( prevState ) => {
      return { resultsOpened: !prevState.resultsOpened }
    });
  }
  render () {
    if (this.props.show) {
      return (
        <Hoc>
          <Results onClick={this.resultsToggleHandler} show={this.state.resultsOpened} link={this.props.link} />
        </Hoc>
      );
    } else if (this.props.loadingProviders) {
      return <Loader loaderTitle="LOOKING FOR OFFER WHICH SUITS YOU BEST!" />
    } else {
      return null;
    }
  }
};

export default Providers;
