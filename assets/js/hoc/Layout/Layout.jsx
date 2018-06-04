import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../Hoc/Hoc.jsx';
import SavedQuizes from '../../containers/SavedQuizes/SavedQuizes';
import Quiz from '../../containers/Quiz/Quiz';
import Home from '../../containers/Home/Home';
import Product from '../../components/Providers/Product/Product';
import Results from '../../components/Providers/Results/Results';
import Summary from '../../components/Providers/Results/Summary/Summary';
import * as actionCreators from '../../store/actions/navigation';
import SideDrawer from '../../components/Navigation/SideDrawer/SideDrawer';
import Slider from 'react-slick';
import data from '../../data.json';

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
      })

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
                      generatedResults
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
