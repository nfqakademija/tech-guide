import * as actionTypes from '../actions/actions';
import { setCurrentPage, resulstsOpenedToggle, resultsShow, resultsHide } from '../actions/navigation';

const initialState = {
    currentPage: 1,
    activeProviderLogo: "",
};

const getCurrentPage = ( state, action ) => {
    return {
        ...state,
        currentPage: action.currentPage,
        activeProviderLogo: action.activeProviderLogo,
    }
}

const reducer = ( state = initialState, action ) => {
    switch(action.type) {
        case actionTypes.SET_CURRENT_PAGE: return getCurrentPage( state, action );
        default: return state;
    }
}

export default reducer;