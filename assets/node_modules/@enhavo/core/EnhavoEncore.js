const Encore = require('@symfony/webpack-encore');

class EnhavoEncore
{
    constructor()
    {
        this.configs = [];
        this.packages = [];
    }

    /**
     * @param {string} name
     * @param {[]} packages
     * @param {function} encoreCallback
     * @param {function} configCallback
     * @returns {object}
     */
    add(name, packages = [], encoreCallback = null, configCallback = null, )
    {
        Encore.reset();
        Encore.setOutputPath('public/build/'+name+'/');
        Encore.setPublicPath('/build/'+name);

        for(let enhavoPackage of packages) {
            enhavoPackage.initEncore(Encore, name)
        }

        if (encoreCallback) {
            encoreCallback(Encore);
        }

        let config = Encore.getWebpackConfig();
        config.name = name;
        this.configs.push(config);

        for(let enhavoPackage of packages) {
            enhavoPackage.initWebpackConfig(config, name)
        }

        if (configCallback) {
            configCallback(config);
        }

        return config;
    }

    export(name = null)
    {
        if(name) {
            for(let config of this.configs) {
                if(config.name === name) {
                    return config;
                }
            }
            throw 'Config name "' + name + '"does not exists';
        }
        return this.configs;
    }
}

module.exports = new EnhavoEncore;
