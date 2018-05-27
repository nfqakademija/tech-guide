import React from 'react';
import { connect } from 'react-redux';

import Hoc from '../../../hoc/Hoc/Hoc';
import Backdrop from '../Backdrop/Backdrop';
import * as actionCreators from '../../../store/actions/providers';

const modal = (props) => {
    if (props.providersHistorySet && props.showProvidersHistory) {
        return (
            <Hoc>
                <Backdrop clicked={props.onToggleProvidersHistory} />
                {props.children}
            </Hoc>
        );
    } else return null;
}

const mapStateToProps = state => {
    return {
        providersHistorySet: state.providers.providersHistorySet,
        showProvidersHistory: state.providers.showProvidersHistory,
    }
}

const mapDispatchToProps = dispatch => {
    return {
        onToggleProvidersHistory: () => dispatch(actionCreators.toggleProvidersHistory()),
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(modal);