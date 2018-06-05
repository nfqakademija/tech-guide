import * as actionTypes from '../actions/actions';
import { loadProviders } from '../actions/providers';

const initialState = {
  providersInfo: [],
  providersSet: false,
  loadingProviders: false,
  error: false,
  providersHistorySet: false,
  providersHistory: [],
};

const getResults = ( state, action ) => {
  return {
    ...state,
    providersInfo: action.providersInfo,
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

const providersHistSet = ( state, action ) => {
  return {
    ...state,
    providersHistorySet: true,
    providersHistory: action.providersHistory,
  }
}

const loadProv = ( state, action ) => {
  return {
    ...state,
    providersInfo: action.providersInfo,
  }
}

const reducer = ( state = initialState, action ) => {
  switch ( action.type ) {
    case actionTypes.HANDLE_END: return getResults( state, action );
    case actionTypes.LOADING_PROVIDERS: return loadingProviders( state, action );
    case actionTypes.FETCH_PROVIDERS_FAILED: return fetchProvidersFailed( state, action );
    case actionTypes.PROVIDERS_HISTORY_SET: return providersHistSet( state, action );
    case actionTypes.LOAD_PROVIDERS: return loadProv( state, action );
    default: return state;
  }
}

export default reducer;