import axios from 'axios';
import * as actionTypes from './actions';

export const loadingProviders = () => {
  return {
    type: actionTypes.LOADING_PROVIDERS,
    loadingProviders: true,
  }
}

export const setProviders = ( providersInfo ) => {
  return {
    type: actionTypes.HANDLE_END,
    providersInfo: providersInfo,
    providersSet: true,
    loadingProviders: false,
  }
}

export const fetchProvidersFailed = () => {
  return {
    type: actionTypes.FETCH_PROVIDERS_FAILED,
  }
}

export const fetchProviders = ( values ) => {
    return dispatch => {
        dispatch(loadingProviders());
        axios.post('/api/guidebotOffer/' + api_key, {
            data: values,
        })
        .then( (response) => {
            dispatch(setProviders(response.data));
        })
        .catch ( error => {
            dispatch(fetchProvidersFailed());
        });
    }
}

export const providersHistorySet = () => {
  return {
    type: actionTypes.PROVIDERS_HISTORY_SET,
    providersHistorySet: true,
  }
}

export const toggleProvidersHistory = () => {
  return {
    type: actionTypes.TOGGLE_PROVIDERS_HISTORY,
  }
}

export const loadProviders = ( providersInfo ) => {
  return {
    type: actionTypes.LOAD_PROVIDERS,
    providersInfo: providersInfo,
  }
}
