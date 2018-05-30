import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../Hoc/Hoc.jsx';
import SavedQuizes from '../../containers/SavedQuizes/SavedQuizes';
import Quiz from '../../containers/Quiz/Quiz';
import Home from '../../containers/Home/Home';
import Provider from '../../components/Providers/Provider/Provider';
import Summary from '../../components/Providers/Results/Summary/Summary';
import * as actionCreators from '../../store/actions/navigation';
import SideDrawer from '../../components/Navigation/SideDrawer/SideDrawer';
import Slider from 'react-slick';


class Layout extends Component {

  componentDidUpdate() {
    if (this.props.providersSet) {
      this.slider.slickGoTo(this.props.currentPage);
    }
  }

  render() {
    let attachedClasses = [];
    let otherClasses = [];
    if (this.props.showGuidebot) {
        attachedClasses = ["quiz-started"];
        otherClasses = ["priority"];
    }

    const settings = {
        dots: true,
        infinite: false,
        speed: 500,
        arrows: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        initialSlide: 1,
        beforeChange: (oldIndex, newIndex) => {
          this.props.onSetCurrentPage(newIndex);
      },
    }

    const generatedProviders = this.props.providersInfo.map( (providerInfo, index) => {
        let count;
        if (providerInfo.count != '-1') {
          count = `(${providerInfo.count})`;
        }

      return (
        <Provider 
        key={index} 
        link={providerInfo.url} 
        logo={providerInfo.logo} 
        count={count} 
        efficiency={providerInfo.filterUsage}
        />
      );
    });

      return (
        <Hoc>
          <div className="row desktop-main">
            <div className={`card ${attachedClasses.join(' ')}`}>
              <Home />
              <div className={`card__side card__side--back ${otherClasses.join('')}`}>
                <SideDrawer goTo={(index) => this.slider.slickGoTo(index)} cookies={this.props.cookies} />
                {this.props.guidebotDataSet && this.props.showGuidebot ? 
                  <Slider ref={slider => (this.slider = slider)} {...settings} >
                    <SavedQuizes cookies={this.props.cookies}/>
                    <Quiz/> 
                    {this.props.providersSet ?
                      <Summary />
                    : null}
                    {this.props.providersSet ? 
                      generatedProviders
                    : null}
                  </Slider> 
                : null}
              </div>
            </div>
          </div>
        </Hoc>
      );
  }


}

const mapStateToProps = state => {
  return {
    guidebotDataSet: state.guidebot.guidebotDataSet,
    showGuidebot: state.guidebot.showGuidebot,
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

export default connect(mapStateToProps, mapDispatchToProps)(Layout);
