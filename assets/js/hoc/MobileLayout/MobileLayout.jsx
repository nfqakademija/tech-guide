import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../Hoc/Hoc';
import MobileNavigation from '../../components/Navigation/MobileNavigation/MobileNavigation';
import Home from '../../containers/Home/Home';
import Quiz from '../../containers/Quiz/Quiz';
import * as actionCreators from '../../store/actions/guidebot';
import Swipe from 'react-swipe-component';
import Slider from 'react-slick';

class MobileLayout extends Component {
    constructor() {
        super();
        this.state = {
            pageCount: 0,
            showGuidebot: false,
        }
    }

    render() {
        let navigationSteps;
        if (this.state.pageCount == 0) {
            navigationSteps = {
                back: "",
                next: "Start quiz!"
            }
        } else if (this.state.pageCount == 1) {
            navigationSteps = {
                back: "Back",
                next: ""
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
                this.setState({ pageCount: newIndex });
                if (newIndex === 1) {
                    this.setState({ showGuidebot: true });
                } else {
                    this.setState({ showGuidebot: false });
                }
            },
        }
            
        return (
            <Hoc>
                <Slider ref={slider => (this.slider = slider)} {...settings} >
                    <div className="mobile-landing"><Home /></div>
                    <div className="mobile-quiz"><Quiz show={this.state.showGuidebot} /></div>
                </Slider>
                <MobileNavigation clickedBack={() => this.slider.slickPrev()} clickedNext={() => this.slider.slickNext()} next={navigationSteps.next} back={navigationSteps.back} />
            </Hoc>
        );
    }
}

const mapDispatchToProps = dispatch => {
    return {
      onShowGuidebot: () => dispatch(actionCreators.showGuidebot()),
    };
}

export default connect(null, mapDispatchToProps)(MobileLayout);