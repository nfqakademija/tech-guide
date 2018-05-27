import * as actionTypes from './actions';

export const setCurrentPage = ( index ) => {
    return {
        type: actionTypes.SET_CURRENT_PAGE,
        currentPage: index,
    }
}

export const resultsToggle = () => {
    return {
        type: actionTypes.RESULTS_TOGGLE,
    }
}