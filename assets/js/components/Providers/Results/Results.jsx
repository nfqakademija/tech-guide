import React, { Component } from 'react';

const results = (props) => (
  <div className="provider">
    <div className="provider__results">
      {props.productList}
    </div>
  </div>
);

export default results;