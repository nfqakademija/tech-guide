import React from 'react';

const gif = (props) => {
  return (
      <video className="video-gif" poster={props.src} autoPlay loop>
              <source src={props.src} type="video/mp4" />
              Your browser does not support the mp4 video codec.
      </video>
  );
}

export default gif;
