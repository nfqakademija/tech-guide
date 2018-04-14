import React, { Component } from 'react';

import Navigation from '../../components/Navigation/Navigation.jsx';
import Hoc from '../Hoc/Hoc.jsx';

class Layout extends Component {
  render() {
    return (
      <Hoc>
        <Navigation />
        <main>
          {this.props.children}
        </main>
      </Hoc>
    );
  }
}

export default Layout;
