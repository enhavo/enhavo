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
