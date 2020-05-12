
class EncoreRegistryPackage
{
    constructor(config = null)
    {
        this.config = {};
        if(this.config !== null) {
            this.config = config;
        }
    }

    initEncore(Encore, name)
    {
        if(name === 'enhavo') {
            Encore
                .addEntry('enhavo/login', './assets/enhavo/login')
        }
    }

    initWebpackConfig(config, name)
    {

    }
}

module.exports = EncoreRegistryPackage;
