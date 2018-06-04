import React from 'react';
import { connect } from 'react-redux';

const summary = (props) => {

    const generatedSummary = props.providersInfo.map( (providerInfo, index) => {

        let deg = 360 * providerInfo.filterUsage / 100;

        let attachClass;
        if (providerInfo.filterUsage > 50) {
            attachClass = "gt-50";
        }

        let fillStyles = {
            transform: `rotate(${deg}deg)`,
        };

        return (
            <div className="results-summary__provider" key={index}>
                <div className="summary__category provider__logo">
                    <img src={providerInfo.logo} />
                </div>
                <div className="summary__category progress-pie">
                    <div className={`progress-pie-chart ${attachClass}`} data-percent={providerInfo.filterUsage}>
                        <div className="ppc-progress">
                            <div className="ppc-progress-fill" style={fillStyles}></div>
                        </div>
                        <div className="ppc-percents">
                            <div className="pcc-percents-wrapper">
                                <span>{providerInfo.filterUsage}%</span>
                            </div>
                        </div>
                    </div>
                    <div className="info" data-info="Progress bar shows the percentage of your given answers that were used to generate offers just for you.">
                        <img className="progress-bar__info" src="images/information.svg" />
                    </div>
                </div>
                <a className="provider__link" href={providerInfo.url} target="_blank">To Shop ({providerInfo.count})</a>
            </div>
        );
    } )

    return (
        <div className="results-summary">
            {generatedSummary}
        </div>
    );
};

const mapStateToProps = state => {
    return {
        providersInfo: state.providers.providersInfo,
    }
}

export default connect(mapStateToProps)(summary);