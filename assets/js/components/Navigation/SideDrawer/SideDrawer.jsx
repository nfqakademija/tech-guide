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

    return (
        <div className="sideDrawer">
            <div className="sideDrawer__navigation" >
                <div className="sideDrawer__navigation--section sideDrawer__navigation--currentSlide" >
                    <img className="sideDrawer__logo" src={props.image} />
                </div>
                <div className="sideDrawer__navigation--section">
                    <h2>Main</h2>
                    <ul>
                        <li><a href="/" >Start again</a></li>
                        <li className={props.currentPage === 0 ? 'active' : null}><a onClick={() => activateButton(0)} href="#" >Your quizes</a></li>
                        <li className={props.currentPage === 1 ? 'active' : null} ><a onClick={() => activateButton(1)} href="#" >Guidebot</a></li>
                    </ul>
                </div>
                { props.providersSet ?
                    <div className="sideDrawer__navigation--section" >
                        <h2>Results</h2>
                        <ul>
                            <li className={props.currentPage === 2 ? 'active' : null} ><a onClick={() => activateButton(2)} href="#" >Summary</a></li>
                            <li className={props.currentPage === 3 ? 'active' : null} ><a onClick={() => activateButton(3)} href="#" >Topocentras</a></li>
                            <li className={props.currentPage === 4 ? 'active' : null} ><a onClick={() => activateButton(4)} href="#" >1a</a></li>
                            <li className={props.currentPage === 5 ? 'active' : null} ><a onClick={() => activateButton(5)} href="#" >Technorama</a></li>
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
    }
}

const mapDispatchToProps = dispatch => {
    return {
        onSetCurrentPage: ( index ) => dispatch(navigationActionCreators.setCurrentPage( index )),
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(sideDrawer);