const _ = require('lodash');

class EncoreRegistryPackage
{
    constructor(config = null)
    {
        this.config = new Config();

        if(typeof config == 'object') {
            _.merge(this.config, config);
        }
    }

    initEncore(Encore, name)
    {
        if(name === 'enhavo') {
            Encore.addEntry('enhavo/newsletter-stats', './assets/enhavo/newsletter-stats');
        }
    }

    initWebpackConfig(config, name)
    {

    }
}

class Config
{
    constructor() {

    }
}

module.exports = EncoreRegistryPackage;
