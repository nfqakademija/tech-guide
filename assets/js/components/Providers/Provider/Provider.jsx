import React, { Component } from 'react';

const provider = (props) => (
  <div className="provider">
    <div className="provider__results">
      <iframe className="provider__results--iframe" src={props.link} >
        <p>Your browser does not support iframes.</p>
      </iframe>
    </div>
  </div>
);

export default provider;
