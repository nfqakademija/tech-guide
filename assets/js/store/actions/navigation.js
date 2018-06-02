import * as actionTypes from './actions';

export const setCurrentPage = ( index ) => {

    let activeProviderLogo;
    if (index == 3) {
        activeProviderLogo = "images/topo.png";
    } else if (index == 4) {
        activeProviderLogo = "images/1aLogo.svg";
    } else if (index == 5) {
        activeProviderLogo = "images/technorama.png";
    }

    return {
        type: actionTypes.SET_CURRENT_PAGE,
        currentPage: index,
        activeProviderLogo: activeProviderLogo,
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