import React from 'react';
import { connect } from 'react-redux';

import Hoc from '../../../hoc/Hoc/Hoc';

const mobileResults = (props) => {
    const generatedProviders = Object.keys( props.providersInfo )
                                .map( providerKey => {
                                    return (
                                        <Provider 
                                          key={providerKey} 
                                          link={this.props.providersInfo[providerKey].url} 
                                          logo={this.props.providersInfo[providerKey].logo} 
                                          count={count} 
                                          efficiency={this.props.providersInfo[providerKey].filterUsage} 
                                          progressBarLeftSide={progressBarLeftSide} 
                                          progressBarRightSide={progressBarRightSide} 
                                          progressBarPie={progressBarPie} />
                                    );
                                });
    
    return (
        <Hoc>
            {generatedProviders}
        </Hoc>
    );
}

const mapStateToProps = state => {
    return {
      providersInfo: state.providers.providersInfo,
    }
}

export default connect(mapStateToProps)(mobileResults);