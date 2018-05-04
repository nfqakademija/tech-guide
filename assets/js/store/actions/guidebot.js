import axios from 'axios';
import * as actionTypes from './actions';

export const setGuidebotData = ( messages ) => {
    return {
        type: actionTypes.FETCH_GUIDEBOT_DATA,
        messages: messages,
        guidebotDataSet: true,
        loadingGuidebotData: false,
    }
}

export const loadingGuidebotData = () => {
    return {
        type: actionTypes.LOADING_GUIDEBOT_DATA,
        loadingGuidebotData: true,
    }
}

export const fetchGuidebotData = () => {
    return dispatch => {
        dispatch(loadingGuidebotData());
        axios.get( '/api/guidebotSentences' )
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
              dispatch(setGuidebotData(messages))
          });
    }
}

export const showGuidebot = () => {
    return {
        type: actionTypes.SHOW_GUIDEBOT,
        showGuidebot: true,
    }
}