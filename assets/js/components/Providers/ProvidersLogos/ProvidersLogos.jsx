import React from 'react';
import Hoc from '../../../hoc/Hoc/Hoc';
import ProviderLogo from './ProviderLogo/ProviderLogo';

const providersLogos = () => {
    return (
        <div className="providers-logos">
            <ProviderLogo src="images/1aLogo.svg" link="https://www.1a.lt/" /> 
            <ProviderLogo src="images/topo.png" link="https://www.topocentras.lt/" /> 
            <ProviderLogo src="images/technorama.png" link="https://www.technorama.lt/" />
        </div>
    );
}

export default providersLogos;