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
        return (
            <li key={index} index={index+3} className={props.currentPage === index+3 ? 'active' : null} >
                <a className="sidedrawer__navigation" onClick={() => activateButton( index+3 )} href="#" >{providerInfo.name}</a>
            </li>
        );
    } );


    let activeProvider = {
        url: "/",
        logo: "",
    };
    if (props.currentPage > 2) {
        activeProvider = props.providersInfo[props.currentPage-3];
    }

    return (
        <div className="sideDrawer">
            <div className="sideDrawer__navigation" >
                <div className="sideDrawer__navigation--section sideDrawer__navigation--currentSlide" >
                    { props.currentPage > 2 ? 
                        <Hoc>
                            <a className="sideDrawer__logo" href={activeProvider.url} target="_blank">
                                <img src={activeProvider.logo} />
                            </a>
                            <a className="sideDrawer__link" href={activeProvider.url} target="_blank">
                                To Shop ({activeProvider.count})
                            </a> 
                        </Hoc>
                    : 
                        <div className="sideDrawer__default-logo">
                            <span className="sideDrawer__default-logo--main">Techguide</span>
                        </div>
                    }
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