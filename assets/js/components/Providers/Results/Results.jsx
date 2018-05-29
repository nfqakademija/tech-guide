import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../../../hoc/Hoc/Hoc';
import Provider from '../Provider/Provider';
import SideDrawer from '../../Navigation/SideDrawer/SideDrawer';
import Slider from 'react-slick';
import { isMobile } from "react-device-detect";
import * as actionCreators from '../../../store/actions/navigation';

class Results extends Component {

  render() {

      const generatedProviders = Object.keys( this.props.providersInfo )
          .map( providerKey => {
            let count;
            if (this.props.providersInfo[providerKey].count != -1) {
              count = `(${this.props.providersInfo[providerKey].count})`;
            }

            return (
              <Provider 
              key={providerKey} 
              link={this.props.providersInfo[providerKey].url} 
              logo={this.props.providersInfo[providerKey].logo} 
              count={count} 
              efficiency={this.props.providersInfo[providerKey].filterUsage}
              />
            );
          });

    if (this.props.providersSet) {
      return (
        <Hoc>
          <div className="results__summary">
            {generatedProviders}
          </div>
          {generatedProviders}
        </Hoc>
      );
    } else return null;
  }
}

const mapStateToProps = state => {
  return {
    providersInfo: state.providers.providersInfo,
    providersSet: state.providers.providersSet,
    currentPage: state.navigation.currentPage,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onSetCurrentPage: ( index ) => dispatch(actionCreators.setCurrentPage( index )),
  }
}


export default connect(mapStateToProps, mapDispatchToProps)(Results);
