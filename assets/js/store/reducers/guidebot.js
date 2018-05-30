import * as actionTypes from '../actions/actions';
import { onShowLoader } from '../actions/guidebot';

const initialState = {
    messages: [],
    loadingGuidebotData: false,
    guidebotDataSet: false,
    showGuidebot: false,
    showLoader: false,
};

const fetchGuidebotData = ( state, action ) => {
    return {
        ...state,
        messages: action.messages,
        loadingGuidebotData: false,
        guidebotDataSet: true,
    }
  }

const loadingGuidebotData = ( state, action ) => {
    let loading;

    return {
        ...state,
        loadingGuidebotData: action.loadingGuidebotData,
    }
}

const showGuidebot = ( state, action ) => {
    return {
        ...state,
        showGuidebot: true,
    }
}

const showLoader = ( state, action ) => {
    return {
        ...state,
        showLoader: true,
    }
}

const hideLoader = ( state, action ) => {
    return {
        ...state,
        showLoader: false,
    }
}

const reducer = ( state = initialState, action ) => {
    switch ( action.type ) {
        case actionTypes.FETCH_GUIDEBOT_DATA: return fetchGuidebotData( state, action );
        case actionTypes.LOADING_GUIDEBOT_DATA: return loadingGuidebotData( state, action );
        case actionTypes.SHOW_GUIDEBOT: return showGuidebot( state, action );
        case actionTypes.SHOW_LOADER: return showLoader( state, action ); 
        case actionTypes.HIDE_LOADER: return hideLoader( state, action );
        default: return state;
    }
}

export default reducer;