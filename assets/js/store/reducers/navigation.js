import * as actionTypes from '../actions/actions';
import { setCurrentPage, resulstsOpenedToggle } from '../actions/navigation';

const initialState = {
    currentPage: 0,
    resultsOpened: true,
};

const getCurrentPage = ( state, action ) => {
    return {
        ...state,
        currentPage: action.currentPage,
    }
}

const resToggle = ( state, action ) => {
    return {
        ...state,
        resultsOpened: !state.resultsOpened
    }
}

const reducer = ( state = initialState, action ) => {
    switch(action.type) {
        case actionTypes.SET_CURRENT_PAGE: return getCurrentPage( state, action ); 
        case actionTypes.RESULTS_TOGGLE: return resToggle( state, action );
        default: return state;
    }
}

export default reducer;