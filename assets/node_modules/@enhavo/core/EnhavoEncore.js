const Encore = require('@symfony/webpack-encore');

class EnhavoEncore
{
    constructor()
    {
        this.configs = [];
        this.packages = [];
    }

    register(enhavoPackage)
    {
        this.packages.push(enhavoPackage);
        return this;
    }

    /**
     * @param {string} name
     * @param {function} callback
     * @param {function} configCallback
     * @returns {object}
     */
    add(name, callback, configCallback = null)
    {
        Encore.reset();
        Encore.setOutputPath('public/build/'+name+'/');
        Encore.setPublicPath('/build/'+name);

        for(let enhavoPackage of this.packages) {
            enhavoPackage.initEncore(Encore, name)
        }

        callback(Encore);

        let config = Encore.getWebpackConfig();
        config.name = name;
        this.configs.push(config);

        for(let enhavoPackage of this.packages) {
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

    /** @deprecated Please upgrade your webpack.config.js (Follow 0.9 Migration Guide) */
    getWebpackConfig(config)
    {
        throw 'Please upgrade your webpack.config.js (Follow 0.9 Migration Guide)';
    }
}

module.exports = new EnhavoEncore;
