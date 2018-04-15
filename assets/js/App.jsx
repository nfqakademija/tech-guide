import React, { Component } from 'react';
import { Route, Switch } from 'react-router-dom';

import Layout from './hoc/Layout/Layout.jsx';
import Home from './containers/Home/Home.jsx';
import Quiz from './containers/Quiz/Quiz.jsx';

class App extends Component {
  render() {
    return (
      <Layout>
        <Switch>
            <Route path="/guidebot" component={Quiz} />
            <Route path="/" exact component={Home} />
        </Switch>
      </Layout>
    );
  }
}

export default App;
