require('bootstrap');
import ReactDOM from 'react-dom';
import { BrowserRouter } from 'react-router-dom';
import React from 'react';
import App from './App.jsx';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware, compose } from 'redux';
import thunk from 'redux-thunk';


const app = (
  <BrowserRouter>
      <App />
  </BrowserRouter>
);

ReactDOM.render( app, document.getElementById('root'));
