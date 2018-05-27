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
        const settings = {
          dots: true,
          infinite: false,
          speed: 500,
          arrows: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          beforeChange: (oldIndex, newIndex) => {
            this.props.onSetCurrentPage(newIndex);
        },
      }

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
          { this.props.resultsOpened ? 
          <Hoc>
            <SideDrawer goTo={(index) => this.slider.slickGoTo(index)} />
            <Slider ref={slider => (this.slider = slider)} {...settings} >
              <div className="results__summary">
                {generatedProviders}
              </div>
              {generatedProviders}
            </Slider> 
          </Hoc>
          : null}
        </Hoc>
      );
    } else {
      return null;
    }
  }
}

const mapStateToProps = state => {
  return {
    providersInfo: state.providers.providersInfo,
    providersSet: state.providers.providersSet,
    currentPage: state.navigation.currentPage,
    resultsOpened: state.navigation.resultsOpened,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    onSetCurrentPage: ( index ) => dispatch(actionCreators.setCurrentPage( index )),
  }
}


export default connect(mapStateToProps, mapDispatchToProps)(Results);
