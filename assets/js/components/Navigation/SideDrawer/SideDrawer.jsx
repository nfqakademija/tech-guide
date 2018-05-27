import React, { Component } from 'react';
import { connect } from 'react-redux';

import Hoc from '../../../hoc/Hoc/Hoc';
import * as navigationActionCreators from '../../../store/actions/navigation';
import * as providersActionCreators from '../../../store/actions/providers';

class SideDrawer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            activePage: 0,
        }
    }

    activateButton = (index) => {
        if (typeof index === 'number') {
            this.props.goTo(index);
        }
        this.props.onSetCurrentPage(index);
    }

    render() {
        let attachedClasses = ['sideDrawer', 'sidedrawer--open'];

        return (
            <Hoc>
                <div className={attachedClasses.join(' ')}>
                    <div className="sideDrawer__navigation" >
                        <div className="sideDrawer__navigation--section sideDrawer__navigation--currentSlide" >
                            <img className="sideDrawer__logo" src={this.props.image} />
                        </div>
                        <div className="sideDrawer__navigation--section">
                        <h2>Main</h2>
                        <ul>
                            <li className={this.props.currentPage === 'Again' ? 'active' : null} ><a onClick={() => this.activateButton('Again')} href="/" >Start again</a></li>
                            {/* <li className={this.props.currentPage === 'Guidebot' ? 'active' : null} ><a onClick={() => this.activateButton('Guidebot')} href="#" >Guidebot</a></li> */}
                            { this.props.providersHistorySet ? <li className={this.props.showProvidersHistory ? 'active' : null} ><a onClick={this.props.onToggleProvidersHistory} href="#" >Your quizes</a></li> : null}
                        </ul>
                        </div>
                        { this.props.providersSet ?
                        <div className="sideDrawer__navigation--section" >
                            <h2>Results</h2>
                            <ul>
                                <li className={this.props.currentPage === 0 ? 'active' : null} ><a onClick={() => this.activateButton(0)} href="#" >Summary</a></li>
                                <li className={this.props.currentPage === 1 ? 'active' : null} ><a onClick={() => this.activateButton(1)} href="#" >Topocentras</a></li>
                                <li className={this.props.currentPage === 2 ? 'active' : null} ><a onClick={() => this.activateButton(2)} href="#" >1a</a></li>
                                <li className={this.props.currentPage === 3 ? 'active' : null} ><a onClick={() => this.activateButton(3)} href="#" >Technorama</a></li>
                            </ul>
                        </div>
                        : null}
                    </div>
                </div>
            </Hoc>
        ); 
    }
}

const mapStateToProps = state => {
    return {
        providersInfo: state.providers.providersInfo,
        showGuidebot: state.guidebot.showGuidebot,
        providersSet: state.providers.providersSet,
        currentPage: state.navigation.currentPage,
        providersHistorySet: state.providers.providersHistorySet,
        showProvidersHistory: state.providers.showProvidersHistory,
    }
}

const mapDispatchToProps = dispatch => {
    return {
        onSetCurrentPage: ( index ) => dispatch(navigationActionCreators.setCurrentPage( index )),
        onToggleProvidersHistory: () => dispatch(providersActionCreators.toggleProvidersHistory()),
        onResultsToggle: () => dispatch(navigationActionCreators.resultsToggle()),
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(SideDrawer);