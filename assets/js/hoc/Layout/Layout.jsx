import React, { Component } from 'react';

import Navigation from '../../components/Navigation/Navigation';
import SideDrawer from '../../components/SideDrawer/SideDrawer';
import Hoc from '../Hoc/Hoc.jsx';

class Layout extends Component {
  render() {
    return (
      <Hoc>
        <SideDrawer />
        <main>
          {this.props.children}
        </main>
      </Hoc>
    );
  }
}

export default Layout;
