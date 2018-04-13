import React, { Component } from 'react';

import Guidebot from '../../components/Guidebot/Guidebot.jsx';
import Hoc from '../Hoc/Hoc.jsx';

class Layout extends Component {
  render() {
    return (
      <Hoc>
        <main>
          {this.props.children}
        </main>
        <Guidebot />
      </Hoc>
    );
  }
}

export default Layout;
