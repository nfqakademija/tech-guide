import * as actionTypes from '../actions/actions';

const initialState = {
    messages: [],
    loadingGuidebotData: false,
    guidebotDataSet: false,
    showGuidebot: false,
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
    return {
        ...state,
        loadingGuidebotData: true,
    }
}

const showGuidebot = ( state, action ) => {
    return {
        ...state,
        showGuidebot: true,
    }
}

const reducer = ( state = initialState, action ) => {
    switch ( action.type ) {
        case actionTypes.FETCH_GUIDEBOT_DATA: return fetchGuidebotData( state, action );
        case actionTypes.LOADING_GUIDEBOT_DATA: return loadingGuidebotData( state, action );
        case actionTypes.SHOW_GUIDEBOT: return showGuidebot( state, action ); 
        default: return state;
    }
}

export default reducer;