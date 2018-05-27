import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../Hoc/Hoc';
import MobileNavigation from '../../components/Navigation/MobileNavigation/MobileNavigation';
import Home from '../../containers/Home/Home';
import SavedQuizes from '../../containers/SavedQuizes/SavedQuizes';
import Quiz from '../../containers/Quiz/Quiz';
import Provider from '../../components/Providers/Provider/Provider';
import MobileResults from '../../components/Providers/Results/MobileResults';
import * as actionCreators from '../../store/actions/guidebot';
import * as navigationActionCreators from '../../store/actions/navigation';
import Slider from 'react-slick';

class MobileLayout extends Component {
    constructor() {
        super();
        this.state = {
            pageCount: 1,
            numberOfSlides: 3,
            showGuidebot: false,
        }
    }

    componentDidUpdate() {
        if (this.props.providersSet) {
            this.slider.slickNext();
        }
        let numberOfSlides;
        numberOfSlides = document.querySelectorAll('.slick-slide').length;
        if (numberOfSlides !== this.state.numberOfSlides) {
            this.setState({ numberOfSlides: numberOfSlides });
        }
    }

    render() {
        let navigationSteps;
        if (this.props.providersHistorySet && this.props.currentPage === 0) {
            navigationSteps = {
                back: "",
                next: "Home",
            }        
        } else if ((!this.props.providersHistorySet &&  this.props.currentPage === 0) || (this.props.providersHistorySet && this.props.currentPage === 1)) {
            if (this.props.providersHistorySet) {
                navigationSteps = {
                    back: "History",
                    next: "Start quiz!",
                }
            } else {
                navigationSteps = {
                    back: "",
                    next: "Start quiz!",
                }
            }
        } else if ((!this.props.providersHistorySet &&  this.props.currentPage === 1) || (this.props.providersHistorySet && this.props.currentPage === 2)) {
            navigationSteps = {
                back: "Back",
                next: "",
            }

            if (this.props.providersSet) {
                navigationSteps.next = "Results"
            }
        } else {
            if (this.state.numberOfSlides-1 === this.props.currentPage) {
                navigationSteps = {
                    back: "Back",
                    next: "",
                }
            } else {
                navigationSteps = {
                    back: "Back",
                    next: "Next",
                }
            }
        }

        let show;
        const settings = {
            dots: true,
            infinite: false,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            initialSlide: this.props.currentPage,
            beforeChange: (oldIndex, newIndex) => {
                if ( (this.props.providersHistorySet && newIndex === 2) || ( !this.props.providersHistorySet && newIndex === 1 ) ) {
                    this.setState({ showGuidebot: true });
                }
                this.setState({ pageCount: newIndex });
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
            
        return (
            <div className="mobile-main">
                <Slider ref={slider => (this.slider = slider)} {...settings} >
                    { this.props.providersHistorySet ? <div className="mobile-savedQuizes"><SavedQuizes /></div> : null}
                    <div className="mobile-landing"><Home /></div>
                    <div className="mobile-quiz">
                        { this.state.showGuidebot ?                  
                            <Quiz /> 
                        : null}
                    </div>
                    { this.props.providersSet ?
                        <div className="results__summary">
                            {generatedProviders}
                        </div>
                    : null
                    }
                    {generatedProviders}
                </Slider>
                <MobileNavigation clickedBack={() => this.slider.slickPrev()} clickedNext={() => this.slider.slickNext()} next={navigationSteps.next} back={navigationSteps.back} />
            </div>
        );
    }
}

const mapStateToProps = state => {
    return {
      loadingGuidebotData: state.guidebot.loadingGuidebotData,
      guidebotDataSet: state.guidebot.guidebotDataSet,
      showGuidebot: state.guidebot.showGuidebot,
      loadingProviders: state.providers.loadingProviders,
      providersSet: state.providers.providersSet,
      providersInfo: state.providers.providersInfo,
      providersHistorySet: state.providers.providersHistorySet,
      currentPage: state.navigation.currentPage,
    }
  }

const mapDispatchToProps = dispatch => {
    return {
      onShowGuidebot: () => dispatch(actionCreators.showGuidebot()),
      onSetCurrentPage: ( index ) => dispatch(navigationActionCreators.setCurrentPage( index )),
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(MobileLayout);