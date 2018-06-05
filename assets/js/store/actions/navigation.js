import * as actionTypes from './actions';

export const setCurrentPage = ( index ) => {
    return {
        type: actionTypes.SET_CURRENT_PAGE,
        currentPage: index,
    }
}