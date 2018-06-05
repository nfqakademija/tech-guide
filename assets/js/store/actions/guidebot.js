import axios from 'axios';
import * as actionTypes from './actions';

export const setGuidebotData = ( messages ) => {
    return {
        type: actionTypes.FETCH_GUIDEBOT_DATA,
        messages: messages,
        guidebotDataSet: true,
    }
}

export const loadingGuidebotData = (bool) => {
    let toggleLoading;
    if (bool == 'true') {
        toggleLoading = true;
    } else {
        toggleLoading = false;
    }
    return {
        type: actionTypes.LOADING_GUIDEBOT_DATA,
        loadingGuidebotData: toggleLoading,
    }
}

export const fetchGuidebotData = () => {
    return dispatch => {
        dispatch(loadingGuidebotData('true'));
        axios.get('/api/guidebotSentences/' + api_key)
          .then( response => {
              const messages = [];

              for (let i = 0; i < response.data.messages.greeting.length; i++) {
                messages.push(response.data.messages.greeting[i]);
              }

              for (let i = 0; i < response.data.messages.questions.length; i++) {
                messages.push(response.data.messages.questions[i]);
              }

              for (let i = 0; i < response.data.messages.options.length; i++) {
                messages.push(response.data.messages.options[i]);
              }
              dispatch(setGuidebotData(messages));
              dispatch(loadingGuidebotData('false'));
        });
    }
}

export const showGuidebot = () => {
    return {
        type: actionTypes.SHOW_GUIDEBOT,
        showGuidebot: true,
    }
}