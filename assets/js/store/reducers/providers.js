import * as actionTypes from '../actions/actions';

const initialState = {
  urls: [],
  providersSet: false,
  loadingProviders: false,
  error: false,
};

const getResults = ( state, action ) => {
  return {
    ...state,
    urls: action.urls,
    providersSet: true,
    loadingProviders: false,
  }
}

const loadingProviders = ( state, action ) => {
  return {
    ...state,
    loadingProviders: true,
  }
}

const fetchProvidersFailed = ( state, action ) => {
  return {
    ...state,
    error: true,
  }
}

const reducer = ( state = initialState, action ) => {
  switch ( action.type ) {
    case actionTypes.HANDLE_END: return getResults( state, action );
    case actionTypes.LOADING_PROVIDERS: return loadingProviders( state, action );
    case actionTypes.FETCH_PROVIDERS_FAILED: return fetchProvidersFailed( state, action );
    default: return state;
  }
}

export default reducer;