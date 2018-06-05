import ReactDOM from 'react-dom';
import React from 'react';
import App from './App';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware, compose, combineReducers } from 'redux';
import thunk from 'redux-thunk';
import providersReducer from './store/reducers/providers';
import guidebotReducer from './store/reducers/guidebot';
import navigationReducer from './store/reducers/navigation';

const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;

const rootReducer = combineReducers({
    providers: providersReducer,
    guidebot: guidebotReducer,
    navigation: navigationReducer,
});

const store = createStore(rootReducer, composeEnhancers(
    applyMiddleware(thunk)
));

const app = (
    <Provider store={store}>
        <App />
    </Provider>
);

ReactDOM.render( app , document.getElementById('root'));
