import axios from 'axios';
import * as actionTypes from './actions';

export const loadingProviders = () => {
  return {
    type: actionTypes.LOADING_PROVIDERS,
    loadingProviders: true,
  }
}

export const setProviders = ( urls ) => {
  return {
    type: actionTypes.HANDLE_END,
    urls: urls,
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
        axios.post('/api/guidebotOffer', {
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
