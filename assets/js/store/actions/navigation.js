import * as actionTypes from './actions';

export const setCurrentPage = ( index ) => {
    return {
        type: actionTypes.SET_CURRENT_PAGE,
        currentPage: index,
    }
}

export const resultsShow = () => {
    return {
        type: actionTypes.RESULTS_SHOW,
        resultsOpened: true,
    }
}

export const resultsHide = () => {
    return {
        type: actionTypes.RESULTS_HIDE,
        resultsOpened: false,
    }
}

export const resultsToggle = () => {
    return {
        type: actionTypes.RESULTS_TOGGLE,
    }
}