require('bootstrap');
import ReactDOM from 'react-dom';
import { BrowserRouter } from 'react-router-dom';
import React from 'react';
import App from './App.jsx';


const app = (
  <BrowserRouter>
      <App />
  </BrowserRouter>
);

ReactDOM.render( app, document.getElementById('root'));
