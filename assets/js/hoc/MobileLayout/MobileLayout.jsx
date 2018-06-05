import React, { Component } from 'react';
import { connect } from 'react-redux';
import Slider from 'react-slick';

import Hoc from '../Hoc/Hoc';
import MobileNavigation from '../../components/Navigation/MobileNavigation/MobileNavigation';
import Home from '../../containers/Home/Home';
import SavedQuizes from '../../components/SavedQuizes/SavedQuizes';
import Summary from '../../components/Providers/Results/Summary/Summary';
import Quiz from '../../components/Quiz/Quiz';
import Product from '../../components/Providers/Product/Product';
import Results from '../../components/Providers/Results/Results';
import * as guidebotActionCreators from '../../store/actions/guidebot';
import * as navigationActionCreators from '../../store/actions/navigation';

class MobileLayout extends Component {

    componentDidMount() {
        document.documentElement.style.background = 'none';
    }

    componentDidUpdate() {
        if (this.props.providersSet) {
            this.slider.slickGoTo(this.props.currentPage);
        }
    }

    render() {

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

        let activeProvider = {
            url: "/",
            logo: "",
        };
        if (this.props.currentPage > 3) {
            activeProvider = this.props.providersInfo[this.props.currentPage-4];
        }
            
        return (
            <Hoc>
                <div className="mobile-header">
                    <a href={activeProvider.url} target="_blank"><img className="mobile-header__logo" src={activeProvider.logo} /></a>
                </div>
                <div className="mobile-main">
                    <Slider ref={slider => (this.slider = slider)} {...settings} >
                        <div className="mobile-savedQuizes">
                            <SavedQuizes />
                        </div>
                        <div className="mobile-landing">
                            <Home />
                        </div>
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
                <MobileNavigation clickedBack={() => this.slider.slickPrev()} clickedNext={() => this.slider.slickNext()} />
            </Hoc>
        );
    }
}

const mapStateToProps = state => {
    return {
      showGuidebot: state.guidebot.showGuidebot,
      providersSet: state.providers.providersSet,
      providersInfo: state.providers.providersInfo,
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
