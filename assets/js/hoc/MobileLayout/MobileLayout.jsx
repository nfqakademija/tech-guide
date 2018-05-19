import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../Hoc/Hoc';
import MobileNavigation from '../../components/Navigation/MobileNavigation/MobileNavigation';
import Home from '../../containers/Home/Home';
import Quiz from '../../containers/Quiz/Quiz';
import Provider from '../../components/Providers/Provider/Provider';
import MobileResults from '../../components/Providers/Results/MobileResults';
import * as actionCreators from '../../store/actions/guidebot';
import Slider from 'react-slick';

class MobileLayout extends Component {
    constructor() {
        super();
        this.state = {
            pageCount: 0,
            showGuidebot: false,
        }
    }

    componentDidUpdate() {
        if (this.props.providersSet) {
            this.slider.slickNext();
        }
    }

    render() {
        let navigationSteps;
        if (this.state.pageCount === 0) {
            navigationSteps = {
                back: "",
                next: "Start quiz!",
            }
        } else if (this.state.pageCount === 1) {
            navigationSteps = {
                back: "Back",
                next: "",
            }

            if (this.props.providersSet) {
                navigationSteps.next = "Results"
            }
        } else {
            navigationSteps = {
                back: "Back",
                next: "",
            }
        }

        let show;
        const settings = {
            dots: true,
            infinite: false,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            beforeChange: (oldIndex, newIndex) => {
                if (newIndex === 1) {
                    this.setState({ showGuidebot: true });
                }
                this.setState({ pageCount: newIndex });
            },
        }

        const generatedProviders = Object.keys( this.props.providersInfo )
                .map( providerKey => {
                let count;
                if (this.props.providersInfo[providerKey].count != -1) {
                    count = `(${this.props.providersInfo[providerKey].count})`;
                }

                let progressBarRightSide;
                let progressBarLeftSide;
                let progressBarPie;
                if (this.props.providersInfo[providerKey].filterUsage <= 50) {
                    progressBarRightSide = {
                        transform: `rotate(${this.props.providersInfo[providerKey].filterUsage/100*360}deg)`,
                    },
                    progressBarLeftSide = {
                        display: "none",
                    }
                } else {
                    progressBarRightSide = {
                        transform: "rotate(180deg)",
                    }
                    progressBarLeftSide = {
                        transform: `rotate(${this.props.providersInfo[providerKey].filterUsage/100*360}deg)`,
                    }
                    progressBarPie = {
                        clip: "rect(auto, auto, auto, auto)",
                    }
                }
            
                return (
                    <Provider 
                        key={providerKey} 
                        link={this.props.providersInfo[providerKey].url} 
                        logo={this.props.providersInfo[providerKey].logo} 
                        count={count} 
                        efficiency={this.props.providersInfo[providerKey].filterUsage} 
                        progressBarLeftSide={progressBarLeftSide} 
                        progressBarRightSide={progressBarRightSide} 
                        progressBarPie={progressBarPie} />
                    );
        });
            
        return (
            <Hoc>
                <Slider ref={slider => (this.slider = slider)} {...settings} >
                    <div className="mobile-landing"><Home /></div>
                    <div className="mobile-quiz">
                        { this.state.showGuidebot ?                  
                            <Quiz /> 
                        : null}
                    </div>
                    {/* { this.props.providersSet ? <MobileResults /> : null } */}
                    {generatedProviders}
                </Slider>
                <MobileNavigation clickedBack={() => this.slider.slickPrev()} clickedNext={() => this.slider.slickNext()} next={navigationSteps.next} back={navigationSteps.back} />
            </Hoc>
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
    }
  }

const mapDispatchToProps = dispatch => {
    return {
      onShowGuidebot: () => dispatch(actionCreators.showGuidebot()),
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(MobileLayout);