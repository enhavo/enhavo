const Encore = require('@symfony/webpack-encore');
const ServiceLoader = require('@enhavo/dependency-injection/Webpack/ServiceLoader');

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

    add(name, callback)
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

        config.module.rules.push({
            test: ServiceLoader.test(),
            use: [{loader: ServiceLoader.path(), options: {}}]
        });

        for(let enhavoPackage of this.packages) {
            enhavoPackage.initWebpackConfig(config, name)
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