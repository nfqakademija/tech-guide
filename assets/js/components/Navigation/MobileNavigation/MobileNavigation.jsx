import React from 'react';
import { connect } from 'react-redux';

const mobileNavigation = (props) => {
    let numberOfSlides = document.querySelectorAll('.slick-slide').length;
    let navigationSteps;
    if (props.currentPage === 0) {
        navigationSteps = {
            back: "",
            next: "Home",
        }
    } else if ( props.currentPage === 1 && !props.showGuidebot) {
        navigationSteps = {
            back: "History",
            next: "Start quiz!",
        }
    } else if ( props.currentPage === 1  && props.showGuidebot ) {
        navigationSteps = {
            back: "History",
            next: "Chat",
        }
    } else if ( props.currentPage === 2 ) {
        navigationSteps = {
            back: "Back",
            next: "",
        }

        if ( props.providersSet ) {
            navigationSteps.next = "Results"
        }
    } else {
        if ( numberOfSlides-1 === props.currentPage ) {
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

    return (
        <div className="mobile-navigation">
            <button className="mobile-navigation__button mobile-navigation__back" onClick={props.clickedBack} >{navigationSteps.back}</button>
            <button className="mobile-navigation__button mobile-navigation__next" onClick={props.clickedNext} >{navigationSteps.next}</button>
        </div>
    );
};

const mapStateToProps = state => {
    return {
      showGuidebot: state.guidebot.showGuidebot,
      providersSet: state.providers.providersSet,
      currentPage: state.navigation.currentPage,
    }
}

export default connect(mapStateToProps)(mobileNavigation);