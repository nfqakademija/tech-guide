import React from 'react';

const product = (props) => (
  <div className="provider__product">
  <div className="product__image">
  <a className="product__logo" href={props.url} target="_blank">
    <img src={props.img} />
  </a>
  </div>
  <div className="product__content">
    <h3 className="product__title">{props.title}</h3>
    <div className="product__price">{props.price}<span className="price__currency">€</span></div>
    <a className="product__link" href={props.url} target="_blank">Preview</a>
  </div>
</div>
);

export default product;