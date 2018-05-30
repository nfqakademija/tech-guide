import React from 'react';
import { connect } from 'react-redux';

import Hoc from '../../../hoc/Hoc/Hoc';
import * as navigationActionCreators from '../../../store/actions/navigation';
import * as providersActionCreators from '../../../store/actions/providers';

const sideDrawer = (props) => {

    const activateButton = (index) => {
        props.goTo(index);
        props.onSetCurrentPage(index);
    }

    const generatedProvidersNavigation = props.providersInfo.map( (providerInfo, index) => {
        let leftSide = 'https://www.';
        let leftSideLength = leftSide.length;
        let urlLength = providerInfo.url.length;
        let url = providerInfo.url.substring(leftSideLength, urlLength);
        let provider = url.substring(0, url.indexOf("."));
        provider = provider.charAt(0).toUpperCase() + provider.slice(1);
        return (
            <li key={index} index={index+3} className={props.currentPage === index+3 ? 'active' : null} >
                <a className="sidedrawer__navigation" onClick={() => activateButton(index+3)} href="#" >{provider}</a>
            </li>
        );
    } )

    return (
        <div className="sideDrawer">
            <div className="sideDrawer__navigation" >
                <div className="sideDrawer__navigation--section sideDrawer__navigation--currentSlide" >
                    <img className="sideDrawer__logo" src={props.image} />
                </div>
                <div className="sideDrawer__navigation--section">
                    <h2>Main</h2>
                    <ul>
                        <li><a className="sidedrawer__navigation" href="/" >Start again</a></li>
                        <li className={props.currentPage === 0 ? 'active' : null}><a className="sidedrawer__navigation" onClick={() => activateButton(0)} href="#" >Your quizes</a></li>
                        <li className={props.currentPage === 1 ? 'active' : null} ><a className="sidedrawer__navigation" onClick={() => activateButton(1)} href="#" >Guidebot</a></li>
                    </ul>
                </div>
                { props.providersSet ?
                    <div className="sideDrawer__navigation--section" >
                        <h2>Results</h2>
                        <ul>
                            <li className={props.currentPage === 2 ? 'active' : null} ><a className="sidedrawer__navigation" onClick={() => activateButton(2)} href="#" >Summary</a></li>
                            {generatedProvidersNavigation}
                        </ul>
                    </div>
                : null}
            </div>
        </div>
    ); 
}

const mapStateToProps = state => {
    return {
        providersSet: state.providers.providersSet,
        currentPage: state.navigation.currentPage,
        providersHistorySet: state.providers.providersHistorySet,
        providersInfo: state.providers.providersInfo,
    }
}

const mapDispatchToProps = dispatch => {
    return {
        onSetCurrentPage: ( index ) => dispatch(navigationActionCreators.setCurrentPage( index )),
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(sideDrawer);