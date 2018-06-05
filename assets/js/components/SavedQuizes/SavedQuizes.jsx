import React from 'react';
import { connect } from 'react-redux';
import { isMobile } from "react-device-detect";

import Hoc from '../../hoc/Hoc/Hoc';
import * as providersActionCreators from '../../store/actions/providers';
import * as navigationActionCreators from '../../store/actions/navigation';

const savedQuizes = (props) => {

    const setProvidersInfo = (providersInfo) => {
        props.onSetProviders(providersInfo);
        if ( isMobile ) {
            props.onSetCurrentPage(3);
        } else {
            props.onSetCurrentPage(2);
        }
    }

    let visualiseCookie = props.providersHistory.map( (record, index) => {
        let date;
        let categoryLabel;
        let providersInfo = [];
        Object.keys(record).forEach( category => {
            categoryLabel = category;
            record[category].map( provider => {
                providersInfo.push(provider);
                date = provider.date;
                return (
                    <div className="quiz-history__provider" key={index}>
                        <div>{provider}</div>
                    </div>
                );
            } )
        } )

        return (
            <div className="quiz-history__record" key={index}>
                <div className="record__category">{categoryLabel}</div>
                <div className="record__category">{date}</div>
                <div className="record__category">
                    <a href="#" onClick={() => setProvidersInfo(providersInfo)} >Show results</a>
                </div>
            </div>
        );
    })

    return (
        <div className="quiz-history" >{props.providersHistory.length === 0 ? <img className="quiz-history__not-found" src="images/noHistory.svg" /> : visualiseCookie}</div>
    );
}

const mapStateToProps = state => {
    return {
        providersHistory: state.providers.providersHistory,
    }
}

const mapDispatchToProps = dispatch => {
    return {
        onSetProviders: ( providersInfo ) => dispatch(providersActionCreators.setProviders( providersInfo )), 
        onSetCurrentPage: ( index ) => dispatch(navigationActionCreators.setCurrentPage( index )),       
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(savedQuizes);