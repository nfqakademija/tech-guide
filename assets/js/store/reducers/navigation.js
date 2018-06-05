import * as actionTypes from '../actions/actions';
import { setCurrentPage } from '../actions/navigation';

const initialState = {
    currentPage: 1,
};

const getCurrentPage = ( state, action ) => {
    return {
        ...state,
        currentPage: action.currentPage,
    }
}

const reducer = ( state = initialState, action ) => {
    switch(action.type) {
        case actionTypes.SET_CURRENT_PAGE: return getCurrentPage( state, action );
        default: return state;
    }
}

export default reducer;