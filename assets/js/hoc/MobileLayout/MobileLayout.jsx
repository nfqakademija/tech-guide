import React, { Component } from 'react';
import { connect } from 'react-redux';
import Slider from 'react-slick';

import Hoc from '../Hoc/Hoc';
import MobileNavigation from '../../components/Navigation/MobileNavigation/MobileNavigation';
import Home from '../../containers/Home/Home';
import SavedQuizes from '../../containers/SavedQuizes/SavedQuizes';
import Summary from '../../components/Providers/Results/Summary/Summary';
import Quiz from '../../containers/Quiz/Quiz';
import Product from '../../components/Providers/Product/Product';
import Results from '../../components/Providers/Results/Results';
import * as guidebotActionCreators from '../../store/actions/guidebot';
import * as navigationActionCreators from '../../store/actions/navigation';
import data from '../../data.json';

class MobileLayout extends Component {
    constructor() {
        super();
        this.state = {
            numberOfSlides: 3,
            navigationHeight: null,
        }
    }

    componentDidUpdate() {

        if (this.props.providersSet) {
            this.slider.slickGoTo(this.props.currentPage);
        }

        let numberOfSlides;
        numberOfSlides = document.querySelectorAll('.slick-slide').length;
        if (numberOfSlides !== this.state.numberOfSlides) {
            this.setState({ numberOfSlides: numberOfSlides });
        }
    }

    render() {
        let navigationSteps;
        if (this.props.currentPage === 0) {
            navigationSteps = {
                back: "",
                next: "Home",
            }
        } else if ( this.props.currentPage === 1 ) {
            navigationSteps = {
                back: "History",
                next: "Start quiz!",
            }
        } else if (this.props.currentPage === 2) {
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
            initialSlide: 1,
            beforeChange: (oldIndex, newIndex) => {
                if ( newIndex === 2 ) {
                    this.props.onShowGuidebot();
                }
                this.props.onSetCurrentPage(newIndex);
            },
        }

        const generatedResults = this.props.providersInfo.map( (providerInfo, index) => {
            const generatedProductList = providerInfo.articles.map ( (product, index) => {
                return (
                  <Product 
                    key={index}
                    url={product.url}
                    img={product.img}
                    title={product.title}
                    price={product.price}
                  />
                );
            })

            return (
                <Results
                    productList={generatedProductList}
                    key={index}
              />
            );
        });
            
        return (
            <Hoc>
                <div className="mobile-main">
                    <Slider ref={slider => (this.slider = slider)} {...settings} >
                        <div className="mobile-savedQuizes"><SavedQuizes cookies={this.props.cookies}/></div>
                        <div className="mobile-landing"><Home /></div>
                        <div className="mobile-quiz">
                            { this.props.showGuidebot ?                  
                                <Quiz /> 
                            : null}
                        </div>
                        {this.props.providersSet ?
                        <Summary />
                        : null}
                        {this.props.providersSet ? 
                        generatedResults
                        : null}
                    </Slider>
                </div>
                <MobileNavigation style={this.props.height} clickedBack={() => this.slider.slickPrev()} clickedNext={() => this.slider.slickNext()} next={navigationSteps.next} back={navigationSteps.back} />
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
      providersHistorySet: state.providers.providersHistorySet,
      currentPage: state.navigation.currentPage,
    }
  }

const mapDispatchToProps = dispatch => {
    return {
      onShowGuidebot: () => dispatch(guidebotActionCreators.showGuidebot()),
      onSetCurrentPage: ( index ) => dispatch(navigationActionCreators.setCurrentPage( index )),
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(MobileLayout);
