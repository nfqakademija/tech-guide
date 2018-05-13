import React from 'react';
import Hoc from '../../../hoc/Hoc/Hoc';
import ProviderLogo from './ProviderLogo/ProviderLogo';

const providersLogos = () => {
    return (
        <div className="providers-logos">
            <ProviderLogo src="https://m.1a.lt/images/img/logo/logo-2-lt.svg?changed=true" link="https://www.1a.lt/" /> 
            <ProviderLogo src="https://apklausa.lt/system/forms/pics/000/151/533/original/pic.jpg?1473688123" link="https://www.topocentras.lt/" /> 
            <ProviderLogo src="http://www.acme.eu/sites/default/files/technorama-logo.jpg" link="https://www.technorama.lt/" />
        </div>
    );
}

export default providersLogos;