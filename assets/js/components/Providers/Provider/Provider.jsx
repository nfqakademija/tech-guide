import React, { Component } from 'react';

class Provider extends Component {
  constructor(props) {
    super(props);
    this.state = {
      efficiencyStyle: {
        width: 0,
      }
    }
  }

  componentDidMount() {
    let efficiencyStyle = {
      width: `${this.props.efficiency}%`,
    }
    setTimeout(() => {
      this.setState({ efficiencyStyle: efficiencyStyle });
    }, 1000);
  };

  render() {
    return (
      <div className="provider">
        <div className="provider__results">
          <iframe className="provider__results--iframe" src={this.props.link} >
            <p>Your browser does not support iframes.</p>
          </iframe>
        </div>
      </div>
    );
  }
}

export default Provider;
